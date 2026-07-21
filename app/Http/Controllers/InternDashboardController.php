<?php

namespace App\Http\Controllers;

use App\Models\Intern;

class InternDashboardController extends Controller
{
    public function index()
    {
        $intern = Intern::with(['department', 'onboardingChecklists.checklistTemplate'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $evaluation = \App\Models\Evaluation::where('intern_id', $intern->id)
            ->where('status', 'submitted')
            ->with('evaluationScores.criteria')
            ->first();

        $tasksCount = [
            'pending' => $intern->tasks()->where('status', 'pending')->count(),
            'in_progress' => $intern->tasks()->where('status', 'in_progress')->count(),
            'completed' => $intern->tasks()->whereIn('status', ['submitted', 'approved'])->count(),
        ];

        $logbookEntriesCount = $intern->logbookEntries()->count();

        return view('intern.dashboard', compact('intern', 'evaluation', 'tasksCount', 'logbookEntriesCount'));
    }
}
