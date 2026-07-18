<?php

namespace Tests\Feature;

use App\Models\ChecklistTemplate;
use App\Models\Department;
use App\Models\Intern;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternOnboardingGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_onboarding_checklist_items_for_a_new_intern(): void
    {
        $department = Department::factory()->create();

        ChecklistTemplate::factory()->create([
            'dept_id' => null,
            'item_text' => 'Submit national ID',
            'is_required' => true,
            'is_active' => true,
        ]);

        ChecklistTemplate::factory()->create([
            'dept_id' => $department->id,
            'item_text' => 'Attend department orientation',
            'is_required' => true,
            'is_active' => true,
        ]);

        $intern = Intern::factory()->create([
            'dept_id' => $department->id,
        ]);

        $this->assertDatabaseHas('t_onboarding_checklists', [
            'intern_id' => $intern->id,
            'item' => 'Submit national ID',
            'is_required' => true,
            'is_completed' => false,
        ]);

        $this->assertDatabaseHas('t_onboarding_checklists', [
            'intern_id' => $intern->id,
            'item' => 'Attend department orientation',
            'is_required' => true,
            'is_completed' => false,
        ]);
    }

    public function test_it_does_not_duplicate_checklist_items_when_generation_runs_more_than_once(): void
    {
        ChecklistTemplate::factory()->create([
            'dept_id' => null,
            'item_text' => 'Submit national ID',
            'is_required' => true,
            'is_active' => true,
        ]);

        $intern = Intern::factory()->create();

        $intern->generateOnboardingChecklist();
        $intern->generateOnboardingChecklist();

        $this->assertSame(
            1,
            $intern->onboardingChecklists()
                ->where('item', 'Submit national ID')
                ->count()
        );
    }
}
