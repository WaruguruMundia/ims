<?php

namespace Tests\Feature;

use App\Models\CompetencyCriteria;
use App\Models\Department;
use App\Models\Evaluation;
use App\Models\EvaluationScore;
use App\Models\GuestToken;
use App\Models\Intern;
use App\Models\LogbookEntry;
use App\Models\Notification;
use App\Models\Supervisor;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase1ModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_and_associate_task(): void
    {
        $intern = Intern::factory()->create();
        $supervisor = User::factory()->supervisor()->create();

        $task = Task::create([
            'intern_id' => $intern->id,
            'created_by' => $supervisor->id,
            'title' => 'Test Task',
            'description' => 'Test Description',
            'priority' => 'medium',
            'status' => 'pending',
            'due_date' => now()->addDays(7),
        ]);

        $this->assertDatabaseHas('t_tasks', [
            'id' => $task->id,
            'title' => 'Test Task',
        ]);

        $this->assertEquals($intern->id, $task->intern->id);
        $this->assertEquals($supervisor->id, $task->creator->id);
        
        $this->assertTrue($intern->tasks->contains($task));
    }

    public function test_can_create_and_associate_logbook_entry(): void
    {
        $intern = Intern::factory()->create();

        $entry = LogbookEntry::create([
            'intern_id' => $intern->id,
            'entry_date' => now()->toDateString(),
            'entry_type' => 'daily',
            'activities_performed' => 'Coded some models',
            'challenges_encountered' => 'None',
            'skills_developed' => 'Laravel model definition',
        ]);

        $this->assertDatabaseHas('t_logbook_entries', [
            'id' => $entry->id,
            'activities_performed' => 'Coded some models',
        ]);

        $this->assertEquals($intern->id, $entry->intern->id);
        $this->assertTrue($intern->logbookEntries->contains($entry));
    }

    public function test_can_create_and_associate_guest_token_with_active_scope(): void
    {
        $intern = Intern::factory()->create();
        $supervisor = User::factory()->supervisor()->create();

        // 1. Create active token
        $activeToken = GuestToken::create([
            'intern_id' => $intern->id,
            'generated_by' => $supervisor->id,
            'token' => 'active-token-xyz',
            'expires_at' => now()->addHours(2),
            'is_revoked' => false,
        ]);

        // 2. Create expired token
        $expiredToken = GuestToken::create([
            'intern_id' => $intern->id,
            'generated_by' => $supervisor->id,
            'token' => 'expired-token-abc',
            'expires_at' => now()->subHours(2),
            'is_revoked' => false,
        ]);

        // 3. Create revoked token
        $revokedToken = GuestToken::create([
            'intern_id' => $intern->id,
            'generated_by' => $supervisor->id,
            'token' => 'revoked-token-123',
            'expires_at' => now()->addHours(2),
            'is_revoked' => true,
        ]);

        $this->assertDatabaseHas('t_guest_tokens', [
            'token' => 'active-token-xyz',
        ]);

        $this->assertEquals($intern->id, $activeToken->intern->id);
        $this->assertEquals($supervisor->id, $activeToken->generator->id);

        $activeTokens = GuestToken::active()->get();
        $this->assertTrue($activeTokens->contains($activeToken));
        $this->assertFalse($activeTokens->contains($expiredToken));
        $this->assertFalse($activeTokens->contains($revokedToken));
    }

    public function test_can_create_supervisor_profile_and_evaluations_with_scores(): void
    {
        $supervisorUser = User::factory()->supervisor()->create();
        $department = Department::factory()->create();

        $supervisor = Supervisor::create([
            'user_id' => $supervisorUser->id,
            'dept_id' => $department->id,
            'employee_number' => 'EMP-12345',
            'max_intern_capacity' => 5,
        ]);

        $this->assertDatabaseHas('t_supervisors', [
            'id' => $supervisor->id,
            'employee_number' => 'EMP-12345',
        ]);

        $this->assertEquals($supervisorUser->id, $supervisor->user->id);
        $this->assertEquals($department->id, $supervisor->department->id);
        $this->assertEquals($supervisor->id, $supervisorUser->fresh()->supervisor->id);

        // Test evaluation associations
        $intern = Intern::factory()->create();
        $criteria = CompetencyCriteria::create([
            'name' => 'Communication',
            'description' => 'Verbal and written communication skills',
            'max_score' => 10,
            'is_active' => true,
        ]);

        $evaluation = Evaluation::create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
            'overall_feedback' => 'Great performance',
            'status' => 'draft',
        ]);

        $score = EvaluationScore::create([
            'evaluation_id' => $evaluation->id,
            'criteria_id' => $criteria->id,
            'score' => 9,
            'comment' => 'Excellent communicator',
        ]);

        $this->assertEquals($intern->id, $evaluation->intern->id);
        $this->assertEquals($supervisor->id, $evaluation->supervisor->id);
        $this->assertTrue($evaluation->evaluationScores->contains($score));
        $this->assertEquals($criteria->id, $score->criteria->id);
        $this->assertTrue($supervisor->evaluations->contains($evaluation));
    }

    public function test_can_create_custom_notification(): void
    {
        $user = User::factory()->create();

        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'task_assigned',
            'title' => 'New Task Assigned',
            'body' => 'You have been assigned a new task.',
            'data' => ['task_id' => 45],
            'is_read' => false,
        ]);

        $this->assertDatabaseHas('t_notifications', [
            'id' => $notification->id,
            'title' => 'New Task Assigned',
        ]);

        $this->assertEquals($user->id, $notification->user->id);
        $this->assertEquals(45, $notification->data['task_id']);
    }
}
