<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

/** @see \App\ */
class AdminTest extends TestCase
{
    /** @test */
    public function allow_user_with_role_of_admin_to_continue()
    {
        $admin = create_admin();
        $request = $this->app->make(Request::class);
        $request->setUserResolver(function () use ($admin) {
            return $admin;
        });

        $expectedResponse = new Response('allowed', Response::HTTP_OK);
        $next = function () use ($expectedResponse) {
            return $expectedResponse;
        };

        $actualResponse = (new Admin())->handle($request, $next);

        $this->assertSame($expectedResponse, $actualResponse);
    }

    /** @test */
    public function do_not_allow_user_without_admin_role_to_continue()
    {
        $this->expectException(HttpException::class);

        $member = create_member();
        $request = $this->app->make(Request::class);
        $request->setUserResolver(function () use ($member) {
            return $member;
        });

        $response = (new Admin())->handle($request, function () {
        });
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
