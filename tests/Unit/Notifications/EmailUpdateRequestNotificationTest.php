<?php

namespace Tests\Unit\Notifications;

use App\Notifications\EmailUpdateRequestNotification;
use App\Services\SignedUrlGenerator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/** @see \App\Notifications\EmailUpdateRequestNotification */
class EmailUpdateRequestNotificationTest extends TestCase
{
    /** @test */
    public function notification_is_queued()
    {
        $notification = new EmailUpdateRequestNotification(Carbon::now(), 'email@example.com');
        $this->assertInstanceOf(ShouldQueue::class, $notification);
    }

    /** @test */
    public function notification_is_sent_via_email()
    {
        $notification = new EmailUpdateRequestNotification(Carbon::now(), 'email@example.com');

        $this->assertContains('mail', $notification->via(null));
    }

    /** @test */
    public function notification_contains_temporally_signed_url()
    {
        $notification = new EmailUpdateRequestNotification(Carbon::now(), 'email@example.com');

        $user = create_user();

        $signedUrlGenerator = new SignedUrlGenerator();
        $signedUrl = $signedUrlGenerator->forNewEmail($user, 'email@example.com', Carbon::now());

        $this->assertStringContainsString(
            htmlspecialchars($signedUrl),
            $notification->toMail($user)->render()
        );
    }
}
