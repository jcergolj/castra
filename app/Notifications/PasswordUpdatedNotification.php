<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @return array */
    public function via()
    {
        return ['mail'];
    }

    /** @return \Illuminate\Notifications\Messages\MailMessage */
    public function toMail()
    {
        return (new MailMessage())
            ->subject('Your password has been changed')
            ->greeting('Hi there')
            ->line('I just want to let you know that your password has been changed.')
            ->line('If you didn\'t changed it. Please contact us immediately.');
    }

    /** @return array */
    public function toArray()
    {
        return [];
    }
}
