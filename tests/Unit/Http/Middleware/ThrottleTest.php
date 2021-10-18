<?php

namespace Tests\Unit\Http\Middleware;

use Tests\TestCase;

/** @see \App\Http\Middleware\Throttle; */
class ThrottleTest extends TestCase
{
    /**
     * @test
     * @dataProvider routesProvider
     */
    public function throttle_middleware_is_applied_for_routes($route)
    {
        $this->assertContains('throttle', $this->getMiddlewareFor($route));
    }

    public function routesProvider()
    {
        return [
            'Route verification.verify doesn\'t have throttle middleware.' => ['verification.verify'],
            'Route verification.send doesn\'t have throttle middleware.' => ['verification.send'],
        ];
    }
}
