<?php

namespace App\Notifications;

use App\Services\SignedUrlGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class EmailUpdateRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var \App\Services\SignedUrlGenerator */
    public $signedUrlGenerator;

    /** @var \Illuminate\Support\Carbon */
    public $validUntil;

    /** @var string */
    protected $newEmail;

    /**
     * @param  \Illuminate\Support\Carbon  $validUntil
     * @param  string  $newEmail
     * @return void
     */
    public function __construct(Carbon $validUntil, $newEmail)
    {
        $this->signedUrlGenerator = app(SignedUrlGenerator::class);
        $this->validUntil = $validUntil;
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
        $signedUrl = $this->signedUrlGenerator->forNewEmail(
            $notifiable,
            $this->newEmail,
            $this->validUntil
        );

        return (new MailMessage())
            ->subject('Please confirm your new email')
            ->greeting('Hi there')
            ->line('Looks like you requested an email address change. Please confirm it by clicking on the link below.')
            ->action('Confirm email change', $signedUrl)
            ->line('If you didn\'t request it, please let us know.');
    }

    /** @return array */
    public function toArray()
    {
        return [];
    }
}
