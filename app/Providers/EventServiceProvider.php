<?php

namespace App\Providers;

use App\Events\ProfileImageUploaded;
use App\Listeners\ResizeImageListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /** @var array<string, array<int, string>> */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ProfileImageUploaded::class => [
            ResizeImageListener::class,
        ],
    ];

    public function boot(): void
    {
    }
}
