<?php

use App\Http\Controllers\Admin\ChecklistTemplateController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\InternRegistrationController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SupervisorDashboardController;
use App\Http\Controllers\InternDashboardController;
use App\Http\Controllers\OnboardingChecklistController;
use App\Http\Controllers\Supervisor\TaskController as SupervisorTaskController;
use App\Http\Controllers\Supervisor\EvaluationController as SupervisorEvaluationController;
use App\Http\Controllers\Intern\TaskController as InternTaskController;
use App\Http\Controllers\Intern\LogbookController as InternLogbookController;
use App\Http\Controllers\GuestLogbookController;
use App\Http\Controllers\ReportController;
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

            Route::resource('interns', InternRegistrationController::class)
                ->except(['show']);
        });

    // ── Supervisor ─────────────────────────────────────────────
    Route::middleware('role:supervisor')
        ->prefix('supervisor')
        ->name('supervisor.')
        ->group(function () {
            Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])
                ->name('dashboard');

            Route::resource('tasks', SupervisorTaskController::class);
            Route::post('tasks/{task}/review', [SupervisorTaskController::class, 'review'])
                ->name('tasks.review');

            Route::resource('evaluations', SupervisorEvaluationController::class)
                ->except(['index', 'destroy']);

            Route::get('interns/{intern}/logbook', [SupervisorDashboardController::class, 'logbook'])
                ->name('interns.logbook');
        });

    // ── Intern ─────────────────────────────────────────────────
    Route::middleware(['role:intern', 'verified'])
        ->prefix('intern')
        ->name('intern.')
        ->group(function () {
            Route::get('/dashboard', [InternDashboardController::class, 'index'])
                ->name('dashboard');

            Route::resource('tasks', InternTaskController::class)
                ->only(['index', 'show', 'update']);

            Route::resource('logbook', InternLogbookController::class)
                ->only(['index', 'create', 'store', 'edit', 'update']);
            Route::post('logbook/generate-token', [InternLogbookController::class, 'generateToken'])
                ->name('logbook.generate-token');
        });

    // ── Shared (admin + supervisor) ────────────────────────────
    Route::middleware('role:admin,supervisor')
        ->name('shared.')
        ->group(function () {
            Route::get('interns/{intern}/report', [ReportController::class, 'download'])
                ->name('interns.report');
        });
});

// ── Public Guest Access (No auth required) ───────────────────
Route::get('/guest/logbooks/{token}', [GuestLogbookController::class, 'show'])
    ->name('guest.logbooks.show');
