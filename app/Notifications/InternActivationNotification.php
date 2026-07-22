<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InternActivationNotification extends Notification
{
    use Queueable;

    protected string $activationUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $activationUrl)
    {
        $this->activationUrl = $activationUrl;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Activate Your Intern Account')
            ->greeting('Welcome ' . $notifiable->name . '!')
            ->line('Your intern profile has been pre-registered by HR Administrator (Neema Wacuka).')
            ->line('Click the button below to verify your email and set up your account password.')
            ->action('Activate Account', $this->activationUrl)
            ->line('This link is valid for 24 hours.')
            ->line('Thank you for using the Intern Management System!');
    }
}
