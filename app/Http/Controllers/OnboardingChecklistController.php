<?php

namespace App\Http\Controllers;

use App\Models\OnboardingChecklist;
use Illuminate\Http\RedirectResponse;

class OnboardingChecklistController extends Controller
{
    public function complete(OnboardingChecklist $checklistItem): RedirectResponse
    {
        $this->authorize('complete', $checklistItem);

        $checklistItem->update([
            'is_completed' => true,
            'completed_at' => now(),
            'completed_by' => auth()->id(),
        ]);

        return back()->with('status', 'Checklist item marked complete.');
    }
}
