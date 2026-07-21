<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestToken extends Model
{
    use HasFactory;

    protected $table = 't_guest_tokens';

    protected $fillable = [
        'intern_id',
        'generated_by',
        'token',
        'expires_at',
        'is_revoked',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_revoked' => 'boolean',
    ];

    /**
     * Get the intern associated with this guest token.
     */
    public function intern(): BelongsTo
    {
        return $this->belongsTo(Intern::class, 'intern_id');
    }

    /**
     * Get the user (supervisor or admin) who generated the token.
     */
    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    /**
     * Scope a query to only include valid (non-expired and non-revoked) guest tokens.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_revoked', false)
            ->where('expires_at', '>', now());
    }
}
