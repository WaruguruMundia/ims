<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetencyCriteria extends Model
{
    use HasFactory;

    protected $table = 't_competency_criteria';

    protected $fillable = [
        'name',
        'description',
        'max_score',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_score' => 'integer',
    ];

    /**
     * Get the evaluation scores recorded under this competency criterion.
     */
    public function evaluationScores(): HasMany
    {
        return $this->hasMany(EvaluationScore::class, 'criteria_id');
    }
}
