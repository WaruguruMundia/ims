<?php

namespace App\Services;

use App\Models\ChecklistTemplate;
use App\Models\Intern;
use App\Models\OnboardingChecklist;

class OnboardingService
{
    public function initializeChecklist(Intern $intern): void
    {
        $templates = ChecklistTemplate::query()
            ->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('dept_id')->orWhere('dept_id', $intern->dept_id))
            ->orderBy('display_order')
            ->orderBy('id')
            ->get();

        foreach ($templates as $template) {
            $intern->onboardingChecklists()->firstOrCreate(
                [
                    'checklist_template_id' => $template->id,
                ],
                [
                    'item' => $template->item_text,
                    'is_required' => $template->is_required,
                    'is_completed' => false,
                ]
            );
        }
    }
}
