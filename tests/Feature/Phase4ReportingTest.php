<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Intern;
use App\Models\LogbookEntry;
use App\Models\Notification as DbNotification;
use App\Models\Supervisor;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class Phase4ReportingTest extends TestCase
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
            'employee_number' => 'EMP-PHASE4',
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

    public function test_assigning_task_triggers_notification_and_custom_db_record(): void
    {
        $this->actingAs($this->supervisorUser)
            ->post(route('supervisor.tasks.store'), [
                'intern_id' => $this->intern->id,
                'title' => 'Complete Phase 4',
                'priority' => 'high',
                'due_date' => now()->addDays(5)->format('Y-m-d'),
            ])
            ->assertRedirect();

        // Verify custom db notification was written
        $this->assertDatabaseHas('t_notifications', [
            'user_id' => $this->internUser->id,
            'type' => 'task_assigned',
        ]);
    }

    public function test_submitting_logbook_triggers_notification_and_custom_db_record(): void
    {
        $this->actingAs($this->internUser)
            ->post(route('intern.logbook.store'), [
                'entry_date' => now()->format('Y-m-d'),
                'entry_type' => 'daily',
                'activities_performed' => 'Wrote some code',
            ])
            ->assertRedirect();

        // Verify custom db notification was written
        $this->assertDatabaseHas('t_notifications', [
            'user_id' => $this->supervisorUser->id,
            'type' => 'logbook_submitted',
        ]);
    }

    public function test_supervisor_and_admin_can_download_pdf_report(): void
    {
        // 1. Unauthenticated download fails
        $this->get(route('shared.interns.report', $this->intern))
            ->assertRedirect(route('login'));

        // 2. Intern downloading their own report fails with 403
        $this->actingAs($this->internUser)
            ->get(route('shared.interns.report', $this->intern))
            ->assertForbidden();

        // 3. Supervisor can download report for their supervisee
        $response = $this->actingAs($this->supervisorUser)
            ->get(route('shared.interns.report', $this->intern));
            
        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertNotEmpty($response->getContent());

        // 4. Admin can download report for any intern
        $admin = User::factory()->admin()->create();
        $responseAdmin = $this->actingAs($admin)
            ->get(route('shared.interns.report', $this->intern));

        $responseAdmin->assertOk();
        $responseAdmin->assertHeader('content-type', 'application/pdf');
    }
}
