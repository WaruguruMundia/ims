<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $interns = Intern::with(['user', 'department', 'supervisor', 'onboardingChecklists'])
            ->orderBy('created_at', 'desc')
            ->get();

        $groupedInterns = $interns->groupBy(function ($intern) {
            return $intern->supervisor ? $intern->supervisor->name : 'No Supervisor Assigned';
        });

        return view('admin.dashboard', compact('groupedInterns'));
    }
}
