<?php

namespace App\Providers;

use App\Models\Intern;
use App\Observers\InternObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * The public function enables FK enforcement for SQLite, which was off by default
     */
    public function boot(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }
        Intern::observe(InternObserver::class);
    }
}
