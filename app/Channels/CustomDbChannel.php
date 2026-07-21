<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class CustomDbChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (method_exists($notification, 'toCustomDb')) {
            $notification->toCustomDb($notifiable);
        }
    }
}
