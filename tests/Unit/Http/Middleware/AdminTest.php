<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\Admin;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

/** @see \App\Http\Middleware\Admin */
class AdminTest extends TestCase
{
    /** @test */
    public function allow_user_with_role_of_admin_to_continue()
    {
        $request = $this->makeRequestWith(create_admin());

        $expectedResponse = new Response('allowed', Response::HTTP_OK);
        $next = function () use ($expectedResponse) {
            return $expectedResponse;
        };

        $actualResponse = (new Admin)->handle($request, $next);

        $this->assertSame($expectedResponse, $actualResponse);
    }

    /** @test */
    public function do_not_allow_user_without_admin_role_to_continue()
    {
        $this->expectException(HttpException::class);

        $request = $this->makeRequestWith(create_member());

        $response = (new Admin)->handle($request, function () {
        });

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
