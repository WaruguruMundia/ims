<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use App\Models\User;
use App\Models\OnboardingChecklist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OnboardingChecklistController extends Controller
{
    public function complete(Request $request, OnboardingChecklist $checklistItem): RedirectResponse
    {
        Gate::authorize('complete', $checklistItem);

        $checklistItem->markCompletedBy($request->user());

        return back()->with('status', 'Checklist item completed successfully.');
    }

    public function reopen(Request $request, OnboardingChecklist $checklistItem): RedirectResponse
    {
        Gate::authorize('complete', $checklistItem);

        $checklistItem->markIncomplete();

        return back()->with('status', 'Checklist item reopened successfully.');
    }

    // ... existing code ...

    public function test_authorized_user_can_reopen_checklist_item(): void
    {
        $internUser = User::factory()->intern()->create();

        $intern = Intern::factory()->create([
            'user_id' => $internUser->id,
        ]);

        $checklistItem = OnboardingChecklist::factory()->create([
            'intern_id' => $intern->id,
            'is_completed' => true,
            'completed_at' => now(),
            'completed_by' => $internUser->id,
        ]);

        $response = $this
            ->actingAs($internUser)
            ->patch(route('onboarding-checklists.reopen', $checklistItem));

        $response->assertRedirect();

        $this->assertDatabaseHas('t_onboarding_checklists', [
            'id' => $checklistItem->id,
            'is_completed' => false,
            'completed_by' => null,
        ]);

        $this->assertNull($checklistItem->fresh()->completed_at);
    }

    public function test_unauthorized_user_cannot_reopen_checklist_item(): void
    {
        $internUser = User::factory()->intern()->create();
        $otherIntern = Intern::factory()->create();

        $checklistItem = OnboardingChecklist::factory()->create([
            'intern_id' => $otherIntern->id,
            'is_completed' => true,
            'completed_at' => now(),
            'completed_by' => $otherIntern->user_id,
        ]);

        $response = $this
            ->actingAs($internUser)
            ->patch(route('onboarding-checklists.reopen', $checklistItem));

        $response->assertForbidden();

        $this->assertDatabaseHas('t_onboarding_checklists', [
            'id' => $checklistItem->id,
            'is_completed' => true,
            'completed_by' => $otherIntern->user_id,
        ]);

        $this->assertNotNull($checklistItem->fresh()->completed_at);
    }
}
