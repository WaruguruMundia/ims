<?php

namespace Tests\Feature;

use App\Models\Intern;
use App\Models\OnboardingChecklist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternOnboardingProgressTest extends TestCase
{
    use RefreshDatabase;

    public function test_onboarding_progress_is_zero_when_intern_has_no_checklist_items(): void
    {
        $intern = Intern::factory()->create();

        $this->assertSame(0, $intern->onboardingProgressPercentage());
    }

    public function test_onboarding_progress_percentage_is_calculated_correctly(): void
    {
        $intern = Intern::factory()->create();

        OnboardingChecklist::factory()->create([
            'intern_id' => $intern->id,
            'is_completed' => true,
        ]);

        OnboardingChecklist::factory()->create([
            'intern_id' => $intern->id,
            'is_completed' => true,
        ]);

        OnboardingChecklist::factory()->create([
            'intern_id' => $intern->id,
            'is_completed' => false,
        ]);

        OnboardingChecklist::factory()->create([
            'intern_id' => $intern->id,
            'is_completed' => false,
        ]);

        $this->assertSame(50, $intern->onboardingProgressPercentage());
    }

    public function test_required_onboarding_is_incomplete_when_required_item_is_pending(): void
    {
        $intern = Intern::factory()->create();

        OnboardingChecklist::factory()->create([
            'intern_id' => $intern->id,
            'is_required' => true,
            'is_completed' => false,
        ]);

        OnboardingChecklist::factory()->create([
            'intern_id' => $intern->id,
            'is_required' => false,
            'is_completed' => true,
        ]);

        $this->assertFalse($intern->hasCompletedRequiredOnboarding());
    }

    public function test_required_onboarding_is_complete_when_all_required_items_are_complete(): void
    {
        $intern = Intern::factory()->create();

        OnboardingChecklist::factory()->create([
            'intern_id' => $intern->id,
            'is_required' => true,
            'is_completed' => true,
        ]);

        OnboardingChecklist::factory()->create([
            'intern_id' => $intern->id,
            'is_required' => true,
            'is_completed' => true,
        ]);

        $this->assertTrue($intern->hasCompletedRequiredOnboarding());
    }

    public function test_incomplete_optional_items_do_not_block_required_onboarding_completion(): void
    {
        $intern = Intern::factory()->create();

        OnboardingChecklist::factory()->create([
            'intern_id' => $intern->id,
            'is_required' => true,
            'is_completed' => true,
        ]);

        OnboardingChecklist::factory()->create([
            'intern_id' => $intern->id,
            'is_required' => false,
            'is_completed' => false,
        ]);

        $this->assertTrue($intern->hasCompletedRequiredOnboarding());
    }
}
