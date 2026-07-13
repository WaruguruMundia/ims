<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SupervisorDashboardController;
use App\Http\Controllers\InternDashboardController;
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

    // ── Admin ──────────────────────────────────────────────────
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                ->name('dashboard');

            // Intern onboarding, reporting — added here as you build each module
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
