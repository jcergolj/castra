<?php

namespace App\Providers;

use PHPUnit\Framework\Assert;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\assertContains;
use function PHPUnit\Framework\assertArrayHasKey;

class AppServiceProvider extends ServiceProvider
{
    public const PER_PAGE = 10;

    /** @return void */
    public function register()
    {
    }

    /** @return void */
    public function boot()
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
