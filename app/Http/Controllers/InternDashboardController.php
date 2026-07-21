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

        return view('intern.dashboard', compact('intern', 'evaluation'));
    }
}
