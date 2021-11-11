<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\Store;
use Tests\TestCase;

/** @see \App\Http\Middleware\RedirectIfAuthenticated */
class RedirectIfAuthenticatedTest extends TestCase
{
    /** @test */
    public function admin_is_redirected_to_admin_dashboard()
    {
        $this->actingAs(create_admin());
        $request = $this->app->make(Request::class);

        $response = (new RedirectIfAuthenticated)->handle($request, function () {
        });
        $expectedResponse = new RedirectResponse(route('admin.dashboards.index'));
        $expectedResponse->setRequest($request);
        $expectedResponse->setSession($this->app->make(Store::class));

        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function user_is_redirected_to_user_dashboard()
    {
        $this->actingAs(create_user());
        $request = $this->app->make(Request::class);

        $response = (new RedirectIfAuthenticated)->handle($request, function () {
        });
        $expectedResponse = new RedirectResponse(route('dashboards.index'));
        $expectedResponse->setRequest($request);
        $expectedResponse->setSession($this->app->make(Store::class));

        $this->assertEquals($expectedResponse, $response);
    }

    /** @test */
    public function if_not_authenticated_continue()
    {
        $request = $this->app->make(Request::class);
        $expectedResponse = new Response('allowed', Response::HTTP_OK);

        $next = function () use ($expectedResponse) {
            return $expectedResponse;
        };

        $actualResponse = (new RedirectIfAuthenticated)->handle($request, $next);

        $this->assertSame($expectedResponse, $actualResponse);
    }
}
