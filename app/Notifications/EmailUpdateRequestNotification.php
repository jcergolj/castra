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

    public function __construct(Carbon $validUntil, string $newEmail)
    {
        $this->signedUrlGenerator = app(SignedUrlGenerator::class);
        $this->validUntil = $validUntil;
        $this->newEmail = $newEmail;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
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

    public function toArray(): array
    {
        return [];
    }
}
