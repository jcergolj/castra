<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Validator::excludeUnvalidatedArrayKeys();

        Model::unguard();

        Model::shouldBeStrict(! $this->app->isProduction());

        if (env('PREVENT_STRAY_REQUESTS', false)) {
            Http::preventStrayRequests();
        }
    }
}
