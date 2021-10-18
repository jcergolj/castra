<?php

namespace Tests\Unit\Http\Middleware;

use Tests\TestCase;

/** @see \App\Http\Middleware\Authenticate; */
class AuthenticateTest extends TestCase
{
    /**
     * @test
     * @dataProvider dashboardRoutesProvider
     * @dataProvider authRoutesProvider
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
}
