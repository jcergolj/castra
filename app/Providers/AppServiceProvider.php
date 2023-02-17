<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Assert;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertContains;

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

        TestResponse::macro('assertViewHasComponent', function ($componentName) {
            /* @phpstan-ignore-next-line */
            if ($this->exceptions->first() !== null) {
                /* @phpstan-ignore-next-line */
                Assert::fail($this->exceptions->first()->getMessage()." in component {$componentName}");
            }

            /* @phpstan-ignore-next-line */
            assertArrayHasKey($componentName, $this->original->getFactory()->getFinder()->getViews(), "View is missing {$componentName} component.");

            return $this;
        });

        TestResponse::macro('assertMiddlewareIsApplied', function ($middleware) {
            assertContains(
                $middleware,
                Route::getRoutes()->getByName(Route::currentRouteName())->gatherMiddleware(),
                Route::currentRouteName()." route doesn't contains one or more middleware",
            );

            return $this;
        });
    }
}
