<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Http\Response;
use Tests\TestCase;

/** @see \App\Http\Controllers\DashboardController */
class DashboardControllerTest extends TestCase
{
    /** @test */
    public function dashboard_index_view_can_be_rendered()
    {
        $response = $this->actingAs(create_user())
            ->get(route('dashboard.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee('Dashboard');
    }
}
