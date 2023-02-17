<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailUpdateWarningNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $newEmail)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Someone requested an email address change')
            ->greeting('Hi there')
            ->line('Someone has requested an email address update.')
            ->line("From {$notifiable->email} to {$this->newEmail}.")
            ->line('Wasn\'t you?')
            ->line('Please contact us immediately.');
    }

    public function toArray(): array
    {
        return [];
    }
}
