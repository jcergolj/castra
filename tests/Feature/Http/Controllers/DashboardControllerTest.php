<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Http\Response;
use Tests\TestCase;

/** @see \App\Http\Controllers\DashboardController */
class DashboardControllerTest extends TestCase
{
    /** @test */
    public function auth_middleware_is_applied_to_the_index_request()
    {
        $this->get(route('dashboards.index'))
            ->assertMiddlewareIsApplied('auth');
    }

    /** @test */
    public function verified_middleware_is_applied_to_the_index_request()
    {
        $this->get(route('dashboards.index'))
            ->assertMiddlewareIsApplied('verified');
    }

    /** @test */
    public function dashboard_index_view_can_be_rendered()
    {
        $response = $this->actingAs(create_user())
            ->get(route('dashboards.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee('Dashboard');
    }
}
