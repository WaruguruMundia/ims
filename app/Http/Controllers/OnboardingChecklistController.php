<?php

namespace App\Http\Controllers;

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
}
