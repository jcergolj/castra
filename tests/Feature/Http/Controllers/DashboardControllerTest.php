<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

/** @see \App\Http\Controllers\DashboardController */
class DashboardControllerTest extends TestCase
{
    /** @test */
    public function dashboard_index_view_can_be_rendered()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('dashboard.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee('Dashboard');
    }
}
