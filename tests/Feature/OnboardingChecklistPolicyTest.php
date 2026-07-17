<?php

namespace Tests\Feature;

use App\Models\Intern;
use App\Models\OnboardingChecklist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingChecklistPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_complete_any_intern_checklist_item(): void
    {
        $admin = User::factory()->admin()->create();
        $checklistItem = OnboardingChecklist::factory()->create();

        $this->assertTrue($admin->can('complete', $checklistItem));
    }

    public function test_supervisor_can_complete_their_own_supervisee_checklist_item(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = Intern::factory()->create(['supervisor_id' => $supervisor->id]);
        $checklistItem = OnboardingChecklist::factory()->create(['intern_id' => $intern->id]);

        $this->assertTrue($supervisor->can('complete', $checklistItem));
    }

    public function test_supervisor_cannot_complete_checklist_item_for_intern_they_do_not_supervise(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $otherIntern = Intern::factory()->create(); // unrelated supervisor by default
        $checklistItem = OnboardingChecklist::factory()->create(['intern_id' => $otherIntern->id]);

        $this->assertFalse($supervisor->can('complete', $checklistItem));
    }

    public function test_intern_can_complete_their_own_checklist_item(): void
    {
        $internUser = User::factory()->intern()->create();
        $intern = Intern::factory()->create(['user_id' => $internUser->id]);
        $checklistItem = OnboardingChecklist::factory()->create(['intern_id' => $intern->id]);

        $this->assertTrue($internUser->can('complete', $checklistItem));
    }

    public function test_intern_cannot_complete_another_intern_checklist_item(): void
    {
        $internUser = User::factory()->intern()->create();
        $otherIntern = Intern::factory()->create(); // different user_id entirely

        $checklistItem = OnboardingChecklist::factory()->create(['intern_id' => $otherIntern->id]);

        $this->assertFalse($internUser->can('complete', $checklistItem));
    }
}
