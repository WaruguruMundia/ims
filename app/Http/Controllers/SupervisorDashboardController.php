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
}
