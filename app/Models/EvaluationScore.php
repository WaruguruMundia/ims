<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluationScore extends Model
{
    use HasFactory;

    protected $table = 't_evaluation_scores';

    protected $fillable = [
        'evaluation_id',
        'criteria_id',
        'score',
        'comment',
    ];

    protected $casts = [
        'score' => 'integer',
    ];

    /**
     * Get the evaluation parent record.
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    /**
     * Get the competency criteria this score belongs to.
     */
    public function criteria(): BelongsTo
    {
        return $this->belongsTo(CompetencyCriteria::class, 'criteria_id');
    }
}
