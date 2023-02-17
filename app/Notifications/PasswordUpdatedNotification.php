<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->subject('Your password has been changed')
            ->greeting('Hi there')
            ->line('I just want to let you know that your password has been changed.')
            ->line('If you didn\'t changed it. Please contact us immediately.');
    }

    public function toArray(): array
    {
        return [];
    }
}
