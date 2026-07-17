<?php

namespace App\Observers;

use App\Models\Intern;

class InternObserver
{
    public function created(Intern $intern): void
    {
        $intern->generateOnboardingChecklist();
    }

}
