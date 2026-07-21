<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\Notification as DbNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskStatusUpdated extends Notification
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

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', \App\Channels\CustomDbChannel::class];
    }

    /**
     * Custom database channel delivery logic.
     */
    public function toCustomDb(object $notifiable): void
    {
        $info = $this->getNotificationInfo();

        DbNotification::create([
            'user_id' => $notifiable->id,
            'type' => 'task_status_updated',
            'title' => $info['subject'],
            'body' => $info['body'],
            'data' => [
                'task_id' => $this->task->id,
                'status' => $this->task->status
            ],
            'is_read' => false,
        ]);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $info = $this->getNotificationInfo();

        $mailMessage = (new MailMessage)
            ->subject($info['subject'])
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($info['body']);

        if ($this->task->status === 'rejected' && $this->task->reviewer_feedback) {
            $mailMessage->line('Feedback: "' . $this->task->reviewer_feedback . '"');
        }

        return $mailMessage
            ->action($info['action_text'], $info['action_url'])
            ->line('Thank you for using the Intern Management System!');
    }

    /**
     * Get notification subject, body, and action details based on status.
     */
    protected function getNotificationInfo(): array
    {
        switch ($this->task->status) {
            case 'in_progress':
                return [
                    'subject' => 'Task Started: ' . $this->task->title,
                    'body' => 'Intern ' . $this->task->intern->user?->name . ' has started working on the task: "' . $this->task->title . '".',
                    'action_text' => 'View Task Details',
                    'action_url' => route('supervisor.tasks.show', $this->task)
                ];
            case 'submitted':
                return [
                    'subject' => 'Task Submitted: ' . $this->task->title,
                    'body' => 'Intern ' . $this->task->intern->user?->name . ' has submitted deliverables for the task: "' . $this->task->title . '".',
                    'action_text' => 'Review Submission',
                    'action_url' => route('supervisor.tasks.show', $this->task)
                ];
            case 'approved':
                return [
                    'subject' => 'Task Approved: ' . $this->task->title,
                    'body' => 'Your supervisor has approved your submission for the task: "' . $this->task->title . '".',
                    'action_text' => 'View Task Details',
                    'action_url' => route('intern.tasks.show', $this->task)
                ];
            case 'rejected':
                return [
                    'subject' => 'Task Returned for Revision: ' . $this->task->title,
                    'body' => 'Your supervisor has returned the task: "' . $this->task->title . '" for revision.',
                    'action_text' => 'Revise Task',
                    'action_url' => route('intern.tasks.show', $this->task)
                ];
            default:
                return [
                    'subject' => 'Task Status Updated: ' . $this->task->title,
                    'body' => 'The status of the task "' . $this->task->title . '" has been updated to ' . $this->task->status . '.',
                    'action_text' => 'View Task Details',
                    'action_url' => route('dashboard')
                ];
        }
    }
}
