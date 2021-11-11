<?php

namespace Tests\Unit\Providers;

use App\Events\ProfileImageUploaded;
use App\Listeners\ResizeImageListener;
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

    /** @test */
    public function resize_image_listener_is_attached_to_profile_image_uploaded_event()
    {
        Event::fake()->assertListening(ProfileImageUploaded::class, ResizeImageListener::class);
    }
}
