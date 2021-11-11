<?php

namespace Tests\Unit\Notifications;

use App\Notifications\EmailUpdateWarningNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/** @see \App\Notifications\EmailUpdateWarningNotification */
class EmailUpdateWarningNotificationTest extends TestCase
{
    /** @test */
    public function notification_is_queued()
    {
        $notification = new EmailUpdateWarningNotification(Carbon::now(), 'email@example.com');
        $this->assertInstanceOf(ShouldQueue::class, $notification);
    }

    /** @test */
    public function notification_is_sent_via_email()
    {
        $notification = new EmailUpdateWarningNotification('email@example.com');

        $this->assertContains('mail', $notification->via(null));
    }

    /** @test */
    public function notification_contains_original_and_new_email_address()
    {
        $notification = new EmailUpdateWarningNotification('email@example.com');

        $user = create_user(['email' => 'joe@example.com']);

        $this->assertStringContainsString(
            'email@example.com',
            $notification->toMail($user)->render()
        );

        $this->assertStringContainsString(
            'joe@example.com',
            $notification->toMail($user)->render()
        );
    }
}
