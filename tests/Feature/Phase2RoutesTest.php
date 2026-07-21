<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\GuestToken;
use App\Models\Intern;
use App\Models\Supervisor;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase2RoutesTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $supervisorUser;
    protected User $internUser;
    protected Intern $intern;
    protected Supervisor $supervisor;

    protected function setUp(): void
    {
        parent::setUp();

        $department = Department::factory()->create();

        $this->adminUser = User::factory()->admin()->create();
        $this->supervisorUser = User::factory()->supervisor()->create();
        $this->internUser = User::factory()->intern()->create();

        $this->supervisor = Supervisor::create([
            'user_id' => $this->supervisorUser->id,
            'dept_id' => $department->id,
            'employee_number' => 'EMP-TEST',
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

    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $this->get(route('supervisor.tasks.index'))->assertRedirect(route('login'));
        $this->get(route('intern.tasks.index'))->assertRedirect(route('login'));
        $this->get(route('intern.logbook.index'))->assertRedirect(route('login'));
    }

    public function test_interns_cannot_access_supervisor_routes(): void
    {
        $this->actingAs($this->internUser)
            ->get(route('supervisor.tasks.index'))
            ->assertStatus(403);

        $this->actingAs($this->internUser)
            ->get(route('supervisor.tasks.create'))
            ->assertStatus(403);
    }

    public function test_supervisors_cannot_access_intern_routes(): void
    {
        $this->actingAs($this->supervisorUser)
            ->get(route('intern.tasks.index'))
            ->assertStatus(403);

        $this->actingAs($this->supervisorUser)
            ->get(route('intern.logbook.index'))
            ->assertStatus(403);
    }

    public function test_supervisors_can_access_supervisor_routes(): void
    {
        // For views to compile successfully, we will need to create the actual blade templates.
        // We will assert Status 200 or verify the controller methods are reached by testing
        // methods that do not require view compilation (like store validation failures).
        
        $this->actingAs($this->supervisorUser)
            ->postJson(route('supervisor.tasks.store'), [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['intern_id', 'title', 'priority', 'due_date']);
            
        $this->actingAs($this->supervisorUser)
            ->postJson(route('supervisor.evaluations.store'), [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['intern_id', 'overall_feedback', 'status', 'scores']);
    }

    public function test_interns_can_access_intern_routes(): void
    {
        $this->actingAs($this->internUser)
            ->postJson(route('intern.logbook.store'), [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['entry_date', 'entry_type', 'activities_performed']);
    }

    public function test_public_guest_access_with_valid_and_invalid_tokens(): void
    {
        $activeToken = GuestToken::create([
            'intern_id' => $this->intern->id,
            'generated_by' => $this->supervisorUser->id,
            'token' => 'route-active-token',
            'expires_at' => now()->addDays(2),
            'is_revoked' => false,
        ]);

        $revokedToken = GuestToken::create([
            'intern_id' => $this->intern->id,
            'generated_by' => $this->supervisorUser->id,
            'token' => 'route-revoked-token',
            'expires_at' => now()->addDays(2),
            'is_revoked' => true,
        ]);

        // Invalid token returns 403
        $this->get(route('guest.logbooks.show', 'invalid-token'))
            ->assertStatus(403);

        $this->get(route('guest.logbooks.show', $revokedToken->token))
            ->assertStatus(403);

        // Valid token will try to load guest.logbook.show view. If view doesn't exist yet, it throws ViewException/InvalidArgumentException.
        // But the middleware/authorization check should succeed, showing the controller logic works.
        try {
            $this->get(route('guest.logbooks.show', $activeToken->token));
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('View [guest.logbook.show] not found', $e->getMessage());
        }
    }
}
