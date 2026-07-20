<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $table = 't_users';

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password'   => 'hashed',
            'is_active'  => 'boolean',
        ];
    }

    // ── Relationships ──────────────────────────────────────────
    public function role(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Role::class, 'role_id');
    }

    // ── Role helpers ───────────────────────────────────────────
    public function hasRole(string $slug): bool
    {
        return $this->role?->slug === $slug;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isSupervisor(): bool
    {
        return $this->hasRole('supervisor');
    }

    public function isIntern(): bool
    {
        return $this->hasRole('intern');
    }

    // ── Dashboard route per role ───────────────────────────────
    public function dashboardRoute(): string
    {
        return match($this->role?->slug) {
            'admin'      => 'admin.dashboard',
            'supervisor' => 'supervisor.dashboard',
            'intern'     => 'intern.dashboard',
            default      => 'login',
        };
    }
}
