<?php

namespace App\Observers;

use App\Models\LogbookEntry;
use App\Notifications\LogbookSubmitted;

class LogbookEntryObserver
{
    /**
     * Handle the LogbookEntry "created" event.
     */
    public function created(LogbookEntry $entry): void
    {
        $supervisor = $entry->intern->supervisor;
        if ($supervisor) {
            $supervisor->notify(new LogbookSubmitted($entry));
        }
    }
}
