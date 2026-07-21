<?php

namespace Tests\Feature;

use App\Models\CompetencyCriteria;
use App\Models\Department;
use App\Models\GuestToken;
use App\Models\Intern;
use App\Models\LogbookEntry;
use App\Models\Supervisor;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase3ViewsTest extends TestCase
{
    use RefreshDatabase;

    protected User $supervisorUser;
    protected User $internUser;
    protected Intern $intern;
    protected Supervisor $supervisor;
    protected Task $task;

    protected function setUp(): void
    {
        parent::setUp();

        $department = Department::factory()->create();

        $this->supervisorUser = User::factory()->supervisor()->create();
        $this->internUser = User::factory()->intern()->create();

        $this->supervisor = Supervisor::create([
            'user_id' => $this->supervisorUser->id,
            'dept_id' => $department->id,
            'employee_number' => 'EMP-111',
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

        $this->task = Task::create([
            'intern_id' => $this->intern->id,
            'created_by' => $this->supervisorUser->id,
            'title' => 'Read documentation',
            'description' => 'Read everything',
            'priority' => 'high',
            'status' => 'pending',
            'due_date' => now()->addDays(2),
        ]);
    }

    public function test_supervisor_views_load_successfully(): void
    {
        $this->actingAs($this->supervisorUser)
            ->get(route('supervisor.tasks.index'))
            ->assertOk()
            ->assertSee('Read documentation');

        $this->actingAs($this->supervisorUser)
            ->get(route('supervisor.tasks.create'))
            ->assertOk()
            ->assertSee('Select Intern');

        $this->actingAs($this->supervisorUser)
            ->get(route('supervisor.tasks.show', $this->task))
            ->assertOk()
            ->assertSee('Read documentation');

        $this->actingAs($this->supervisorUser)
            ->get(route('supervisor.interns.logbook', $this->intern))
            ->assertOk()
            ->assertSee($this->internUser->name);

        // Competency Criteria needed for evaluation view
        CompetencyCriteria::create([
            'name' => 'Tech skills',
            'max_score' => 10,
            'is_active' => true,
        ]);

        $this->actingAs($this->supervisorUser)
            ->get(route('supervisor.evaluations.create', ['intern_id' => $this->intern->id]))
            ->assertOk()
            ->assertSee('Tech skills');
    }

    public function test_intern_views_load_successfully(): void
    {
        $this->actingAs($this->internUser)
            ->get(route('intern.tasks.index'))
            ->assertOk()
            ->assertSee('Read documentation');

        $this->actingAs($this->internUser)
            ->get(route('intern.tasks.show', $this->task))
            ->assertOk()
            ->assertSee('Read documentation');

        $this->actingAs($this->internUser)
            ->get(route('intern.logbook.index'))
            ->assertOk()
            ->assertSee('Generate Guest Share Link');

        $this->actingAs($this->internUser)
            ->get(route('intern.logbook.create'))
            ->assertOk()
            ->assertSee('Entry Date');
    }

    public function test_guest_views_load_successfully(): void
    {
        $token = GuestToken::create([
            'intern_id' => $this->intern->id,
            'generated_by' => $this->supervisorUser->id,
            'token' => 'guest-view-token',
            'expires_at' => now()->addDays(7),
            'is_revoked' => false,
        ]);

        $this->get(route('guest.logbooks.show', $token->token))
            ->assertOk()
            ->assertSee($this->internUser->name)
            ->assertSee('IMS External Portal');
    }
}
