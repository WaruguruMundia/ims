<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChecklistTemplate extends Model
{
    use HasFactory;
    protected $table = 't_checklist_templates';

    protected $fillable = ['dept_id', 'item_text', 'display_order', 'is_required', 'is_active'];

    protected $casts = ['is_required' => 'boolean', 'is_active' => 'boolean'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    public function onboardingChecklists(): HasMany
    {
        return $this->hasMany(OnboardingChecklist::class, 'checklist_template_id');
    }
}
