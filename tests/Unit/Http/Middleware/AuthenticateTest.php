<?php

namespace Tests\Unit\Http\Middleware;

use Tests\Concerns\TestableMiddleware;
use Tests\TestCase;

/** @see \App\Http\Middleware\Authenticate; */
class AuthenticateTest extends TestCase
{
    use TestableMiddleware;

    /**
     * @test
     * @dataProvider dashboardRoutesProvider
     * @dataProvider authRoutesProvider
     * @dataProvider accountRoutesProvider
     */
    public function authenticate_middleware_is_applied_for_routes($route)
    {
        $this->assertContains('auth', $this->getMiddlewareFor($route));
    }

    public function dashboardRoutesProvider()
    {
        return [
            'Route dashboard doesn\'t have authenticate middleware.' => ['dashboard.index'],
        ];
    }

    public function authRoutesProvider()
    {
        return [
            'Route verification.notice doesn\'t have authenticate middleware.' => ['verification.notice'],
            'Route verification.verify doesn\'t have authenticate middleware.' => ['verification.verify'],
            'Route verification.send doesn\'t have authenticate middleware.' => ['verification.send'],
            'Route logout doesn\'t have authenticate middleware.' => ['logout'],
        ];
    }

    public function accountRoutesProvider()
    {
        return [
            'Route account.profile doesn\'t have authenticate middleware.' => ['account.profile'],
        ];
    }
}
