<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\LogbookEntryObserver;

#[ObservedBy([LogbookEntryObserver::class])]
class LogbookEntry extends Model
{
    use HasFactory;

    protected $table = 't_logbook_entries';

    protected $fillable = [
        'intern_id',
        'entry_date',
        'entry_type',
        'activities_performed',
        'challenges_encountered',
        'skills_developed',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    /**
     * Get the intern who owns the logbook entry.
     */
    public function intern(): BelongsTo
    {
        return $this->belongsTo(Intern::class, 'intern_id');
    }
}
