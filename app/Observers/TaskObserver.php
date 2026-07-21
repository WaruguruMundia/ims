<?php

namespace App\Observers;

use App\Models\Task;
use App\Notifications\TaskAssigned;
use App\Notifications\TaskStatusUpdated;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        $task->intern->user?->notify(new TaskAssigned($task));
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        if ($task->wasChanged('status')) {
            $newStatus = $task->status;

            if (in_array($newStatus, ['in_progress', 'submitted'])) {
                // Notify the creator (supervisor/admin)
                $task->creator?->notify(new TaskStatusUpdated($task));
            } elseif (in_array($newStatus, ['approved', 'rejected'])) {
                // Notify the intern
                $task->intern->user?->notify(new TaskStatusUpdated($task));
            }
        }
    }
}
