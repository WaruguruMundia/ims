<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supervisor extends Model
{
    use HasFactory;

    protected $table = 't_supervisors';

    protected $fillable = [
        'user_id',
        'dept_id',
        'employee_number',
        'max_intern_capacity',
    ];

    protected $casts = [
        'max_intern_capacity' => 'integer',
    ];

    /**
     * Get the user account for the supervisor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the department the supervisor belongs to.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    /**
     * Get the evaluations submitted by this supervisor.
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'supervisor_id');
    }
}
