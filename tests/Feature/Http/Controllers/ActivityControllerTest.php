<?php

namespace Tests\Feature\Http\Controllers;

use App\Providers\AppServiceProvider;
use Illuminate\Http\Response;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\Concerns\TestableMiddleware;
use Tests\TestCase;

/** @see \App\Http\Controllers\Controller */
class ActivityControllerTest extends TestCase
{
    use TestableFormRequest, TestableMiddleware;

    /** @test */
    public function authenticate_middleware_is_applied()
    {
        $this->assertContains('auth', $this->getMiddlewareFor('activities.index'));
    }

    /** @test */
    public function verified_middleware_is_applied()
    {
        $this->assertContains('verified', $this->getMiddlewareFor('activities.index'));
    }

    /** @test */
    public function index_view_can_be_rendered()
    {
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

    // undo action for deleted items

    // purge activities every 3 months
}
