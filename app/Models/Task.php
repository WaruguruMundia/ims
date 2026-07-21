<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\TaskObserver;

#[ObservedBy([TaskObserver::class])]
class Task extends Model
{
    use HasFactory;

    protected $table = 't_tasks';

    protected $fillable = [
        'intern_id',
        'created_by',
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'deliverable_notes',
        'submission_notes',
        'reviewer_feedback',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the intern assigned to the task.
     */
    public function intern(): BelongsTo
    {
        return $this->belongsTo(Intern::class, 'intern_id');
    }

    /**
     * Get the user (supervisor or admin) who created the task.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
