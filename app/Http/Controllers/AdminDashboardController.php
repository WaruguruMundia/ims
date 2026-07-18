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

        return view('admin.dashboard', compact('interns'));
    }
}
