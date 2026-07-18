<?php

namespace Tests\Feature;

use App\Models\Intern;
use App\Models\OnboardingChecklist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOnboardingDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_all_intern_onboarding_statuses(): void
    {
        $admin = User::factory()->admin()->create();
        $supervisor = User::factory()->supervisor()->create();

        $intern = Intern::factory()->create([
            'supervisor_id' => $supervisor->id,
        ]);

        OnboardingChecklist::factory()->create([
            'intern_id' => $intern->id,
            'item' => 'Submit national ID',
            'is_completed' => true,
        ]);

        OnboardingChecklist::factory()->create([
            'intern_id' => $intern->id,
            'item' => 'Attend orientation',
            'is_completed' => false,
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee($intern->user->name);
        $response->assertSee($supervisor->name);
        $response->assertSee('50%');
    }

    public function test_non_admin_cannot_access_admin_onboarding_overview(): void
    {
        $internUser = User::factory()->intern()->create();

        $response = $this
            ->actingAs($internUser)
            ->get(route('admin.dashboard'));

        $response->assertForbidden();
    }
}
