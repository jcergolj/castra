<?php

namespace Tests\Unit\Http\Middleware;

use Tests\Concerns\TestableMiddleware;
use Tests\TestCase;

class SignedTest extends TestCase
{
    use TestableMiddleware;

    /**
     * @test
     * @dataProvider routesProvider
     */
    public function signed_middleware_is_applied_for_routes($route)
    {
        $this->assertContains('signed', $this->getMiddlewareFor($route));
    }

    public function routesProvider()
    {
        return [
            'Route verification.verify doesn\'t have signed middleware.' => ['verification.verify'],
        ];
    }
}
