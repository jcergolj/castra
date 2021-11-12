<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\Concerns\TestableMiddleware;
use Tests\TestCase;

/** @see \App\Http\Controllers\Controller */
class UserControllerTest extends TestCase
{
    use TestableFormRequest, TestableMiddleware;

    /**
     * @test
     * @dataProvider middlewareRouteDataProvider
     */
    public function middleware_is_applied_for_routes($middleware, $route)
    {
        $this->assertContains($middleware, $this->getMiddlewareFor($route));
    }

    public function middlewareRouteDataProvider()
    {
        return [
            'Admin middleware is not applied to admin.users.index.' => ['admin', 'admin.users.index'],
            'Auth middleware is not applied to admin.users.index.' => ['auth', 'admin.users.index'],
            'Verified middleware is not applied to admin.users.index.' => ['verified', 'admin.users.index'],
        ];
    }

    /** @test */
    public function index_view_can_be_rendered()
    {
        $response = $this->actingAs(create_admin())
            ->get(route('admin.users.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    // assert search, role select and per page are visible
    // assert order by email and role and created is possible
    // assert filter is applied to model
    // assert paginate is applied to model
    // assert order by is applied to model

    // assert search functionally
    // assert pagination

    // /** @test */
    // public function create_view_can_be_rendered()
    // {
    //     $response = $this->actingAs(create_user())
    //         ->get(route('xyz.create'));

    //     $response->assertStatus(Response::HTTP_OK)
    //         ->assertViewHasForm('id="xyz"', 'POST', route('.store'))
    //         ->assertFormHasCSRF()
    //         //
    //         ->assertFormHasSubmitButton();
    // }

    // /** @test */
    // public function create_view_form_is_inside_turbo_frame()
    // {
    //     $response = $this->actingAs(create_user())
    //         ->get(route('.create'));

    //     $response->assertStatus(Response::HTTP_OK)
    //         ->assertElementHasChild(
    //             'turbo-frame[id="xyz"]',
    //             'form[action="'.route('.store').'"]'
    //         );
    // }

    // /** @test */
    // public function xyz_can_be_created()
    // {
    //     $response = $this->actingAs(create_user())
    //         ->from(route('.index'))
    //         ->post(route('.store'), [
    //             //
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //         ->assertRedirect(route(''))
    //         ->assertSessionHas('status');

    //     // other assertions
    // }

    // /** @test */
    // public function store_has_xyz_request()
    // {
    //     $this->assertRouteUsesFormRequest('{ route }', { request }::class);
    // }

    // /** @test */
    // public function edit_view_can_be_rendered()
    // {
    //     $response = $this->actingAs(create_user())
    //         ->get(route('.edit', 'xyz'));

    //     $response->assertStatus(Response::HTTP_OK)
    //         ->assertViewHasForm('id="xyz"', 'PATCH', route('.update'))
    //         ->assertFormHasCSRF()
    //         //
    //         ->assertFormHasSubmitButton();
    // }

    // /** @test */
    // public function edit_view_form_is_inside_turbo_frame()
    // {
    //     $response = $this->actingAs(create_user())
    //         ->get(route('.edit'));

    //     $response->assertStatus(Response::HTTP_OK)
    //         ->assertElementHasChild(
    //             'turbo-frame[id="xyz"]',
    //             'form[action="'.route('.update').'"]'
    //         );
    // }

    // /** @test */
    // public function xyz_can_be_updated()
    // {
    //     $response = $this->actingAs(create_user())
    //         ->from(route('.show', ))
    //         ->patch(route('.update', ), [
    //             //
    //         ]);

    //     $response->assertStatus(Response::HTTP_FOUND)
    //         ->assertRedirect(route('.show'))
    //         ->assertSessionHas('status');

    //     // other assertions
    // }

    // /** @test */
    // public function update_has_xyz_request()
    // {
    //     $this->assertRouteUsesFormRequest('{ route }', { request }::class);
    // }

    // /** @test */
    // public function xyz_can_be_destroyed()
    // {
    //     $response = $this->actingAs(create_user())
    //         ->from(route('.index'))
    //         ->delete('.destroy');

    //     $response->assertStatus(Response::HTTP_FOUND)
    //         ->assertRedirect(route('.index'))
    //         ->assertSessionHas('status');
    // }
}
