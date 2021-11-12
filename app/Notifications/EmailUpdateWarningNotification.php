<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailUpdateWarningNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var string */
    public $newEmail;

    /**
     * @param  string  $newEmail
     * @return void
     */
    public function __construct($newEmail)
    {
        $this->newEmail = $newEmail;
    }

    /** @return array */
    public function via()
    {
        return ['mail'];
    }

    /**
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject('Someone requested an email address change')
            ->greeting('Hi there')
            ->line('Someone has requested an email address update.')
            ->line("From {$notifiable->email} to {$this->newEmail}.")
            ->line('Wasn\'t you?')
            ->line('Please contact us immediately.');
    }

    /** @return array */
    public function toArray()
    {
        return [];
    }
}
