<?php

use App\Http\Controllers\Admin\ChecklistTemplateController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\InternRegistrationController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SupervisorDashboardController;
use App\Http\Controllers\InternDashboardController;
use App\Http\Controllers\OnboardingChecklistController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Root → redirect to login or dashboard
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route(Auth::user()->dashboardRoute());
    }
    return redirect()->route('login');
});

// Auth routes (login / logout / password reset — no register)
require __DIR__.'/auth.php';

// ── Authenticated routes ───────────────────────────────────────────────────

Route::middleware('auth')->group(function () {

    // Generic /dashboard → bounces to the role-specific one
    Route::get('/dashboard', function () {
        return redirect()->route(Auth::user()->dashboardRoute());
    })->name('dashboard');

    Route::patch('/onboarding-checklists/{checklistItem}/complete', [OnboardingChecklistController::class, 'complete'])
        ->name('onboarding-checklists.complete');

    Route::patch('/onboarding-checklists/{checklistItem}/reopen', [OnboardingChecklistController::class, 'reopen'])
        ->name('onboarding-checklists.reopen');

    // ── Admin ──────────────────────────────────────────────────
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                ->name('dashboard');

            Route::resource('checklist-templates', ChecklistTemplateController::class)
                ->except(['show']);

            Route::resource('departments', DepartmentController::class)
                ->except(['show', 'destroy']);
            Route::patch('departments/{department}/toggle-active', [DepartmentController::class, 'toggleActive'])
                ->name('departments.toggle-active');

            Route::get('interns/create', [InternRegistrationController::class, 'create'])
                ->name('interns.create');
            Route::post('interns', [InternRegistrationController::class, 'store'])
                ->name('interns.store');
        });

    // ── Supervisor ─────────────────────────────────────────────
    Route::middleware('role:supervisor')
        ->prefix('supervisor')
        ->name('supervisor.')
        ->group(function () {
            Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])
                ->name('dashboard');

            // Task review, evaluations — added here
        });

    // ── Intern ─────────────────────────────────────────────────
    Route::middleware('role:intern')
        ->prefix('intern')
        ->name('intern.')
        ->group(function () {
            Route::get('/dashboard', [InternDashboardController::class, 'index'])
                ->name('dashboard');

            // Task updates, logbook entries — added here
        });

    // ── Shared (admin + supervisor) ────────────────────────────
    Route::middleware('role:admin,supervisor')
        ->name('shared.')
        ->group(function () {
            // Routes accessible by both roles go here
        });
});
