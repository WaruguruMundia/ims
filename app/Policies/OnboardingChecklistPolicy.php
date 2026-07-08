<?php

namespace App\Policies;

use App\Models\OnboardingChecklist;
use App\Models\User;

class OnboardingChecklistPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function complete(User $user, OnboardingChecklist $checklistItem): bool
    {
        if ($user->isSupervisor()) {
            return $checklistItem->intern->supervisor_id === $user->id;
        }

        if ($user->isIntern()) {
            return $checklistItem->intern->user_id === $user->id;
        }

        return false;
    }
}
