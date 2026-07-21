<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use HasFactory;

    protected $table = 't_evaluations';

    protected $fillable = [
        'intern_id',
        'supervisor_id',
        'overall_feedback',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the intern evaluated.
     */
    public function intern(): BelongsTo
    {
        return $this->belongsTo(Intern::class, 'intern_id');
    }

    /**
     * Get the supervisor who evaluated the intern.
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id');
    }

    /**
     * Get the criteria-specific scores for this evaluation.
     */
    public function evaluationScores(): HasMany
    {
        return $this->hasMany(EvaluationScore::class, 'evaluation_id');
    }
}
