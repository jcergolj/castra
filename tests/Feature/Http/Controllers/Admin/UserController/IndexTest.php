<?php

namespace Tests\Feature\Http\Controllers\Admin\UserController;

use App\Models\User;
use Illuminate\Http\Response;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Mockery\MockInterface;
use Tests\TestCase;

/** @see \App\Http\Controllers\Admin\UserController::index() */
class IndexTest extends TestCase
{
    use TestableFormRequest;

    /** @test */
    public function auth_middleware_is_applied_to_the_index_request()
    {
        $this->get(route('admin.users.index'))
            ->assertMiddlewareIsApplied('auth');
    }

    /** @test */
    public function admin_middleware_is_applied_to_the_index_request()
    {
        $this->get(route('admin.users.index'))
            ->assertMiddlewareIsApplied('admin');
    }

    /** @test */
    public function verified_middleware_is_applied_to_the_index_request()
    {
        $this->get(route('admin.users.index'))
            ->assertMiddlewareIsApplied('verified');
    }

    /** @test */
    public function index_has_filter_paginate_and_order_by_options()
    {
        $this->partialMock(User::class, function (MockInterface $mock) {
            create_user(['email' => 'joe@example.com']);
            $mock->shouldReceive('filter')->once()->andReturn($mock);
            $mock->shouldReceive('orderBy')->once()->andReturn($mock);
            $mock->shouldReceive('paginate')->once()->andReturn(User::paginate());
        });

        $response = $this->actingAs(create_admin())
            ->get(route('admin.users.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHas('users')
            ->assertViewHas('per_page', config('castra.per_page'))
            ->assertViewHas('order_by', 'id')
            ->assertViewHas('order_by_direction', 'asc')
            ->assertSee('joe@example.com');
    }

    /** @test */
    public function index_view_has_search_role_and_per_page_filters()
    {
        $this->withoutExceptionHandling();

        $response = $this->actingAs(create_admin())
            ->get(route('admin.users.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHasForm('id="filters"', 'get', route('admin.users.index'))
            ->assertFormHasCSRF()
            ->assertFormHasTextInput('search')
            ->assertFormHasDropdown('role')
            ->assertFormHasDropdown('per_page')
            ->assertFormHasSubmitButton();
    }

    /** @test */
    public function index_view_has_order_by_email_role_and_created()
    {
        $response = $this->actingAs(create_admin())
            ->get(route('admin.users.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertElementHasChild('table', 'a[href="'.route('admin.users.index', ['order_by' => 'email', 'order_by_direction' => 'desc']).'"]')
            ->assertElementHasChild('table', 'a[href="'.route('admin.users.index', ['order_by' => 'role', 'order_by_direction' => 'desc']).'"]')
            ->assertElementHasChild('table', 'a[href="'.route('admin.users.index', ['order_by' => 'created_at', 'order_by_direction' => 'desc']).'"]');
    }
}
