<?php

namespace App\Notifications;

use App\Models\LogbookEntry;
use App\Models\Notification as DbNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LogbookSubmitted extends Notification
{
    use Queueable;

    protected LogbookEntry $entry;

    /**
     * Create a new notification instance.
     */
    public function __construct(LogbookEntry $entry)
    {
        $this->entry = $entry;
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
        $internName = $this->entry->intern->user?->name ?? 'An intern';

        DbNotification::create([
            'user_id' => $notifiable->id,
            'type' => 'logbook_submitted',
            'title' => 'New Logbook Entry from ' . $internName,
            'body' => 'Recorded a ' . $this->entry->entry_type . ' log for ' . $this->entry->entry_date->format('Y-m-d') . '.',
            'data' => [
                'entry_id' => $this->entry->id,
                'intern_id' => $this->entry->intern_id
            ],
            'is_read' => false,
        ]);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $internName = $this->entry->intern->user?->name ?? 'An intern';

        return (new MailMessage)
            ->subject('New Logbook Entry: ' . $internName)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Intern ' . $internName . ' has recorded a new ' . $this->entry->entry_type . ' logbook entry.')
            ->line('Entry Date: ' . $this->entry->entry_date->format('Y-m-d'))
            ->line('Activities: "' . substr($this->entry->activities_performed, 0, 150) . '..."')
            ->action('Review Logbook', route('supervisor.interns.logbook', $this->entry->intern_id))
            ->line('Thank you for using the Intern Management System!');
    }
}
