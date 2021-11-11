<?php

namespace Tests\Unit\Notifications;

use App\Notifications\PasswordUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Tests\TestCase;

/** @see \App\Notifications\EmailUpdateRequestNotification */
class PasswordUpdatedNotificationTest extends TestCase
{
    /** @test */
    public function notification_is_queued()
    {
        $notification = new PasswordUpdatedNotification;
        $this->assertInstanceOf(ShouldQueue::class, $notification);
    }

    /** @test */
    public function notification_is_sent_via_email()
    {
        $notification = new PasswordUpdatedNotification;

        $this->assertContains('mail', $notification->via(null));
    }

    /** @test */
    public function notification_contains_line()
    {
        $notification = new PasswordUpdatedNotification;
        $this->assertStringContainsString(
            'password has been changed',
            $notification->toMail(null)->render()
        );
    }
}
