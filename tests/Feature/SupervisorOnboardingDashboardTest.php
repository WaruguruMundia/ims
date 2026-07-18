<?php

namespace Tests\Feature;

use App\Models\Intern;
use App\Models\OnboardingChecklist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupervisorOnboardingDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_supervisor_can_view_assigned_interns(): void
    {
        $supervisor = User::factory()->supervisor()->create();

        $assignedIntern = Intern::factory()->create([
            'supervisor_id' => $supervisor->id,
        ]);

        OnboardingChecklist::factory()->create([
            'intern_id' => $assignedIntern->id,
            'item' => 'Submit national ID',
            'is_completed' => false,
        ]);

        $response = $this
            ->actingAs($supervisor)
            ->get(route('supervisor.dashboard'));

        $response->assertOk();
        $response->assertSee($assignedIntern->user->name);
        $response->assertSee('Submit national ID');
    }

    public function test_supervisor_cannot_view_unassigned_interns_on_dashboard(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $otherSupervisor = User::factory()->supervisor()->create();

        $assignedIntern = Intern::factory()->create([
            'supervisor_id' => $supervisor->id,
        ]);

        $unassignedIntern = Intern::factory()->create([
            'supervisor_id' => $otherSupervisor->id,
        ]);

        $response = $this
            ->actingAs($supervisor)
            ->get(route('supervisor.dashboard'));

        $response->assertOk();
        $response->assertSee($assignedIntern->user->name);
        $response->assertDontSee($unassignedIntern->user->name);
    }

    public function test_supervisor_can_complete_assigned_intern_checklist_item(): void
    {
        $supervisor = User::factory()->supervisor()->create();

        $intern = Intern::factory()->create([
            'supervisor_id' => $supervisor->id,
        ]);

        $checklistItem = OnboardingChecklist::factory()->create([
            'intern_id' => $intern->id,
            'is_completed' => false,
            'completed_at' => null,
            'completed_by' => null,
        ]);

        $response = $this
            ->actingAs($supervisor)
            ->patch(route('onboarding-checklists.complete', $checklistItem));

        $response->assertRedirect();

        $this->assertDatabaseHas('t_onboarding_checklists', [
            'id' => $checklistItem->id,
            'is_completed' => true,
            'completed_by' => $supervisor->id,
        ]);
    }

    public function test_supervisor_can_reopen_assigned_intern_checklist_item(): void
    {
        $supervisor = User::factory()->supervisor()->create();

        $intern = Intern::factory()->create([
            'supervisor_id' => $supervisor->id,
        ]);

        $checklistItem = OnboardingChecklist::factory()->create([
            'intern_id' => $intern->id,
            'is_completed' => true,
            'completed_at' => now(),
            'completed_by' => $supervisor->id,
        ]);

        $response = $this
            ->actingAs($supervisor)
            ->patch(route('onboarding-checklists.reopen', $checklistItem));

        $response->assertRedirect();

        $this->assertDatabaseHas('t_onboarding_checklists', [
            'id' => $checklistItem->id,
            'is_completed' => false,
            'completed_by' => null,
        ]);

        $this->assertNull($checklistItem->fresh()->completed_at);
    }
}
