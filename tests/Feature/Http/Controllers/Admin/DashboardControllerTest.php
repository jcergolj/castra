<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Tests\Concerns\TestableMiddleware;
use Tests\TestCase;

/** @see \App\Http\Controllers\Admin\DashboardController */
class DashboardControllerTest extends TestCase
{
    use TestableMiddleware;

    /**
     * @test
     *
     * @dataProvider dashboardRoutesProvider
     */
    public function authenticate_middleware_is_applied($route)
    {
        $this->assertContains('auth', $this->getMiddlewareFor($route));
    }

    /**
     * @test
     *
     * @dataProvider dashboardRoutesProvider
     */
    public function verified_middleware_is_applied($route)
    {
        $this->assertContains('verified', $this->getMiddlewareFor($route));
    }

    /**
     * @test
     *
     * @dataProvider dashboardRoutesProvider
     */
    public function admin_middleware_is_applied($route)
    {
        $this->assertContains('admin', $this->getMiddlewareFor($route));
    }

    /** @test */
    public function dashboard_index_view_can_be_rendered()
    {
        $response = $this->actingAs(create_admin())
            ->get(route('admin.dashboards.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee('Dashboard');
    }

    public function dashboardRoutesProvider()
    {
        return [
            'Route dashboard doesn\'t have middleware.' => ['admin.dashboards.index'],
        ];
    }
}
