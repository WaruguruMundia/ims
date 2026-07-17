<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OnboardingChecklist extends Model
{
    use HasFactory;
    protected $table = 't_onboarding_checklists';

    protected $fillable = [
        'intern_id',
        'checklist_template_id',
        'item',
        'is_required',
        'is_completed',
        'completed_at',
        'completed_by',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function intern(): BelongsTo
    {
        return $this->belongsTo(Intern::class, 'intern_id');
    }

    public function checklistTemplate(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplate::class, 'checklist_template_id');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
