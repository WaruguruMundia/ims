<?php

namespace App\Observers;

use App\Models\Intern;
use App\Services\OnboardingService;

class InternObserver
{
    public function created(Intern $intern): void
    {
        app(OnboardingService::class)->initializeChecklist($intern);
    }
}
