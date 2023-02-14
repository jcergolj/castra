<?php

namespace Tests\Unit\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/** @see \App\Providers\EventServiceProvider */
class EventServiceProviderTest extends TestCase
{
    /** @test */
    public function send_email_verification_notification_listener_is_attached_to_registered_event()
    {
        Event::fake()->assertListening(Registered::class, SendEmailVerificationNotification::class);
    }
}
