<?php

namespace Tests\Unit\Http\Middleware;

use Tests\Concerns\TestableMiddleware;
use Tests\TestCase;

/** @see \App\Http\Middleware\Authenticate; */
class GuestTest extends TestCase
{
    use TestableMiddleware;

    /**
     * @test
     * @dataProvider routesProvider
     */
    public function guest_middleware_is_applied_for_routes($route)
    {
        $this->assertContains('guest', $this->getMiddlewareFor($route));
    }

    public function routesProvider()
    {
        return [
            'Route register doesn\'t have guest middleware.' => ['register'],
            'Route register.store doesn\'t have guest middleware.' => ['register.store'],
            'Route login doesn\'t have guest middleware.' => ['login'],
            'Route login.store doesn\'t have guest middleware.' => ['login.store'],
            'Route password.request doesn\'t have guest middleware.' => ['password.request'],
            'Route password.email doesn\'t have guest middleware.' => ['password.email'],
            'Route password.reset doesn\'t have guest middleware.' => ['password.reset'],
            'Route password.update doesn\'t have guest middleware.' => ['password.update'],
        ];
    }
}
