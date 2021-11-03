<?php

namespace Tests\Unit\Http\Middleware;

use Tests\Concerns\TestableMiddleware;
use Tests\TestCase;

/** @see \App\Http\Middleware\VerifiedUserMiddleware; */
class VerifiedTest extends TestCase
{
    use TestableMiddleware;

    /**
     * @test
     * @dataProvider routesProvider
     * @dataProvider accountRoutesProvider
     */
    public function guest_middleware_is_applied_for_routes($route)
    {
        $this->assertContains('verified', $this->getMiddlewareFor($route));
    }

    public function routesProvider()
    {
        return [
            'Route register doesn\'t have verified middleware.' => ['dashboard.index'],
        ];
    }

    public function accountRoutesProvider()
    {
        return [
            'Route account.profile doesn\'t have authenticate middleware.' => ['account.profile'],
        ];
    }
}
