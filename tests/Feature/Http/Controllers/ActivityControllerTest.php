<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Activity;
use Illuminate\Http\Response;
use App\Providers\AppServiceProvider;
use Jcergolj\FormRequestAssertions\TestableFormRequest;

/** @see \App\Http\Controllers\ActivityController */
class ActivityControllerTest extends TestCase
{
    use TestableFormRequest;

    /** @test */
    function auth_middleware_is_applied_to_the_index_request()
    {
        $this->get(route('activities.index'))
            ->assertMiddlewareIsApplied('auth');
    }

    /** @test */
    function verified_middleware_is_applied_to_the_index_request()
    {
        $this->get(route('activities.index'))
            ->assertMiddlewareIsApplied('verified');
    }

    /** @test */
    public function index_view_can_be_rendered()
    {
        Activity::factory()->create();

        $response = $this->actingAs(create_admin())
            ->get(route('activities.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHas('activities')
            ->assertViewHas('per_page', AppServiceProvider::PER_PAGE)
            ->assertViewHas('order_by', 'created_at')
            ->assertViewHas('order_by_direction', 'desc')
            ->assertSee('joe@example.com');
    }

    // user can't see causer_id

    // purge activities every
}
