<?php

namespace Tests\Feature;

use App\Models\CompetencyCriteria;
use App\Models\Department;
use App\Models\Evaluation;
use App\Models\EvaluationScore;
use App\Models\GuestToken;
use App\Models\Intern;
use App\Models\LogbookEntry;
use App\Models\Supervisor;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase5VerificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $supervisorUser;
    protected User $internUser;
    protected Intern $intern;
    protected Supervisor $supervisor;

    protected function setUp(): void
    {
        parent::setUp();

        $department = Department::factory()->create();

        $this->supervisorUser = User::factory()->supervisor()->create();
        $this->internUser = User::factory()->intern()->create();

        $this->supervisor = Supervisor::create([
            'user_id' => $this->supervisorUser->id,
            'dept_id' => $department->id,
            'employee_number' => 'EMP-VERIFY',
        ]);

        $this->intern = Intern::create([
            'user_id' => $this->internUser->id,
            'dept_id' => $department->id,
            'supervisor_id' => $this->supervisorUser->id,
            'institution' => 'Test Uni',
            'programme' => 'Test Course',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ]);
    }

    /**
     * Test Task Management: Verify task assignment, submission, and approval workflows.
     */
    public function test_task_management_workflow(): void
    {
        // 1. Assign Task (Supervisor)
        $this->actingAs($this->supervisorUser)
            ->post(route('supervisor.tasks.store'), [
                'intern_id' => $this->intern->id,
                'title' => 'Implement Task UI',
                'description' => 'Write all views',
                'priority' => 'high',
                'due_date' => now()->addDays(5)->format('Y-m-d'),
                'deliverable_notes' => 'Code files & tests',
            ])
            ->assertRedirect();

        $task = Task::where('title', 'Implement Task UI')->firstOrFail();
        $this->assertEquals('pending', $task->status);

        // 2. Start Task (Intern)
        $this->actingAs($this->internUser)
            ->patch(route('intern.tasks.update', $task), [
                'status' => 'in_progress',
            ])
            ->assertRedirect();

        $this->assertEquals('in_progress', $task->fresh()->status);

        // 3. Submit Task Deliverables (Intern)
        $this->actingAs($this->internUser)
            ->patch(route('intern.tasks.update', $task), [
                'status' => 'submitted',
                'submission_notes' => 'Done everything!',
            ])
            ->assertRedirect();

        $this->assertEquals('submitted', $task->fresh()->status);
        $this->assertEquals('Done everything!', $task->fresh()->submission_notes);

        // 4. Reject Task / Return for Revision (Supervisor)
        $this->actingAs($this->supervisorUser)
            ->post(route('supervisor.tasks.review', $task), [
                'action' => 'reject',
                'reviewer_feedback' => 'Need more tests.',
            ])
            ->assertRedirect();

        $this->assertEquals('rejected', $task->fresh()->status);
        $this->assertEquals('Need more tests.', $task->fresh()->reviewer_feedback);

        // 5. Re-submit Task (Intern)
        $this->actingAs($this->internUser)
            ->patch(route('intern.tasks.update', $task), [
                'status' => 'submitted',
                'submission_notes' => 'Added tests.',
            ])
            ->assertRedirect();

        // 6. Approve Task (Supervisor)
        $this->actingAs($this->supervisorUser)
            ->post(route('supervisor.tasks.review', $task), [
                'action' => 'approve',
                'reviewer_feedback' => 'Great job!',
            ])
            ->assertRedirect();

        $this->assertEquals('approved', $task->fresh()->status);
        $this->assertEquals('Great job!', $task->fresh()->reviewer_feedback);
    }

    /**
     * Test Logbook Entry: Verify daily/weekly logging permissions and validation.
     */
    public function test_logbook_entry_validation(): void
    {
        // Fail: Backdate error or empty fields
        $this->actingAs($this->internUser)
            ->postJson(route('intern.logbook.store'), [
                'entry_date' => now()->addDay()->format('Y-m-d'), // future date fails
                'entry_type' => 'daily',
                'activities_performed' => '',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['entry_date', 'activities_performed']);

        // Success logging daily activity
        $this->actingAs($this->internUser)
            ->post(route('intern.logbook.store'), [
                'entry_date' => now()->format('Y-m-d'),
                'entry_type' => 'daily',
                'activities_performed' => 'Wrote unit tests today.',
                'challenges_encountered' => 'Minor syntax issue',
                'skills_developed' => 'Laravel routing tests',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('t_logbook_entries', [
            'intern_id' => $this->intern->id,
            'activities_performed' => 'Wrote unit tests today.',
        ]);
    }

    /**
     * Test Guest Token: Verify guest token generation, revocation, and read-only access.
     */
    public function test_guest_token_access(): void
    {
        // 1. Generate token
        $this->actingAs($this->internUser)
            ->post(route('intern.logbook.generate-token'))
            ->assertRedirect();

        $tokenRecord = GuestToken::where('intern_id', $this->intern->id)->active()->firstOrFail();

        // 2. Fetch logbook read-only page via token
        $this->get(route('guest.logbooks.show', $tokenRecord->token))
            ->assertOk()
            ->assertSee($this->internUser->name);

        // 3. Generate a new one (revokes previous)
        $this->actingAs($this->internUser)
            ->post(route('intern.logbook.generate-token'))
            ->assertRedirect();

        $this->assertTrue($tokenRecord->fresh()->is_revoked);
        
        // Old token returns 403
        $this->get(route('guest.logbooks.show', $tokenRecord->token))
            ->assertStatus(403);
    }

    /**
     * Test Performance Evaluation: Verify scoring limits (scores <= max_score).
     */
    public function test_performance_evaluation_scoring(): void
    {
        $criteria = CompetencyCriteria::create([
            'name' => 'Communication',
            'max_score' => 10,
            'is_active' => true,
        ]);

        // Fail: Score exceeds maximum score
        $this->actingAs($this->supervisorUser)
            ->postJson(route('supervisor.evaluations.store'), [
                'intern_id' => $this->intern->id,
                'overall_feedback' => 'Good intern',
                'status' => 'submitted',
                'scores' => [
                    $criteria->id => [
                        'score' => 12, // exceeds max_score (10)
                        'comment' => 'Very good',
                    ]
                ]
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['scores.' . $criteria->id . '.score']);

        // Success: Score is valid
        $this->actingAs($this->supervisorUser)
            ->post(route('supervisor.evaluations.store'), [
                'intern_id' => $this->intern->id,
                'overall_feedback' => 'Good intern',
                'status' => 'submitted',
                'scores' => [
                    $criteria->id => [
                        'score' => 8,
                        'comment' => 'Very good',
                    ]
                ]
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('t_evaluations', [
            'intern_id' => $this->intern->id,
            'overall_feedback' => 'Good intern',
            'status' => 'submitted',
        ]);

        $this->assertDatabaseHas('t_evaluation_scores', [
            'criteria_id' => $criteria->id,
            'score' => 8,
        ]);
    }

    /**
     * Test Intern Date Validation: Admin cannot register intern with past start date.
     */
    public function test_admin_cannot_register_intern_with_past_start_date(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->postJson(route('admin.interns.store'), [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'dept_id' => $this->intern->dept_id,
                'supervisor_id' => $this->supervisorUser->id,
                'institution' => 'Test Uni',
                'programme' => 'Test Course',
                'start_date' => now()->subDay()->format('Y-m-d'), // past date
                'end_date' => now()->addMonths(3)->format('Y-m-d'),
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['start_date']);
    }
}
