<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\Notification as DbNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification
{
    use Queueable;

    protected Task $task;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via(object $notifiable): array
    {
        return ['mail', \App\Channels\CustomDbChannel::class];
    }

    /**
     * Custom database channel delivery logic.
     */
    public function toCustomDb(object $notifiable): void
    {
        DbNotification::create([
            'user_id' => $notifiable->id,
            'type' => 'task_assigned',
            'title' => 'New Task Assigned: ' . $this->task->title,
            'body' => 'Priority: ' . ucfirst($this->task->priority) . ', Due: ' . $this->task->due_date->format('Y-m-d'),
            'data' => ['task_id' => $this->task->id],
            'is_read' => false,
        ]);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have been assigned a new task: ' . $this->task->title)
            ->line('Priority: ' . ucfirst($this->task->priority))
            ->line('Due Date: ' . $this->task->due_date->format('Y-m-d'))
            ->action('View Task Details', route('intern.tasks.show', $this->task))
            ->line('Thank you for using the Intern Management System!');
    }
}
