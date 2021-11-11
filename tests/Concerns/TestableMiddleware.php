<?php

namespace Tests\Concerns;

use Illuminate\Support\Facades\Route;

trait TestableMiddleware
{
    /**
     * Get array of middleware for given route.
     *
     * @param  string  $route
     * @return array
     */
    protected function getMiddlewareFor($route)
    {
        return array_map(function ($middleware) {
            return explode(':', $middleware)[0];
        }, Route::getRoutes()->getByName($route)->gatherMiddleware());
    }
}
