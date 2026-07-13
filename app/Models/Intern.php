<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Intern extends Model
{
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
}
