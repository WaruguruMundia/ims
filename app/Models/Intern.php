<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Intern extends Model
{
    use HasFactory;
    protected $table = 't_interns';

    protected $fillable = [
        'user_id',
        'dept_id',
        'supervisor_id',
        'institution',
        'programme',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * The intern's own login account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    /**
     * The supervisor's user account — supervisor_id references t_users, not t_interns.
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function onboardingChecklists(): HasMany
    {
        return $this->hasMany(OnboardingChecklist::class, 'intern_id');
    }

    public function generateOnboardingChecklist(): void
    {
        $templates = ChecklistTemplate::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('dept_id')
                    ->orWhere('dept_id', $this->dept_id);
            })
            ->orderBy('display_order')
            ->orderBy('id')
            ->get();

        foreach ($templates as $template) {
            $this->onboardingChecklists()->firstOrCreate(
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

    public function onboardingProgressPercentage(): int
    {
        $totalItems = $this->onboardingChecklists()->count();

        if ($totalItems === 0) {
            return 0;
        }

        $completedItems = $this->onboardingChecklists()
            ->where('is_completed', true)
            ->count();

        return (int) round(($completedItems / $totalItems) * 100);
    }

    public function hasCompletedRequiredOnboarding(): bool
    {
        return ! $this->onboardingChecklists()
            ->where('is_required', true)
            ->where('is_completed', false)
            ->exists();
    }
}
