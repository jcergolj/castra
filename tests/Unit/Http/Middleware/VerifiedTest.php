<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\VerifiedUserMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

/** @see \App\Http\Middleware\VerifiedUserMiddleware; */
class VerifiedTest extends TestCase
{
    /**
     * @test
     * @dataProvider routesProvider
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
}
