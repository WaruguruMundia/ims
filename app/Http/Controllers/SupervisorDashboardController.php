<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use Illuminate\View\View;

class SupervisorDashboardController extends Controller
{
    public function index(): View
    {
        $interns = Intern::with(['user', 'department', 'onboardingChecklists'])
            ->where('supervisor_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('supervisor.dashboard', compact('interns'));
    }

    public function logbook(Intern $intern): View
    {
        if ($intern->supervisor_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $intern->load(['user', 'department']);
        $entries = $intern->logbookEntries()->orderBy('entry_date', 'desc')->get();

        return view('supervisor.logbook', compact('intern', 'entries'));
    }
}
