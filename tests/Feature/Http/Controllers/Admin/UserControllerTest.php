<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Activity;
use Mockery\MockInterface;
use App\Enums\ActivityEvents;
use Illuminate\Http\Response;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Config;
use Tests\Concerns\TestableMiddleware;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/** @see \App\Http\Controllers\UserController */
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
            // missing create, store
            'Admin middleware is not applied to admin.users.show.' => ['admin', 'admin.users.show'],
            'Auth middleware is not applied to admin.users.show.' => ['auth', 'admin.users.show'],
            'Verified middleware is not applied to admin.users.show.' => ['verified', 'admin.users.show'],
            'Admin middleware is not applied to admin.users.destroy.' => ['admin', 'admin.users.destroy'],
            'Auth middleware is not applied to admin.users.destroy.' => ['auth', 'admin.users.destroy'],
            'Verified middleware is not applied to admin.users.destroy.' => ['verified', 'admin.users.destroy'],
        ];
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
            ->assertViewHas('per_page', AppServiceProvider::PER_PAGE)
            ->assertViewHas('order_by', 'id')
            ->assertViewHas('order_by_direction', 'asc')
            ->assertSee('joe@example.com');
    }

    /** @test */
    public function index_view_has_search_role_and_per_page_filters()
    {
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

    /** @test */
    public function show_view_can_be_rendered()
    {
        $user = create_user(['email' => 'joe@example.com']);

        $response = $this->actingAs(create_admin())
            ->get(route('admin.users.show', $user));

        $response->assertStatus(Response::HTTP_OK)
            ->assertSee('joe@example.com');
    }

    /** @test */
    public function update_profile_image_turbo_frame_is_on_show_page()
    {
        $user = create_user(['email' => 'joe@example.com']);

        $response = $this->actingAs(create_admin())
            ->get(route('admin.users.show', $user));

        $response->assertStatus(Response::HTTP_OK)
            ->assertElementHasChild('body', 'turbo-frame[id="frame_update_profile_image"]')
            ->assertElementHasChild('turbo-frame[id="frame_update_profile_image"]', 'a[href="'.route('admin.user-images.edit', $user).'"]');
    }

    /** @test */
    public function update_email_turbo_frame_is_on_show_page()
    {
        $user = create_user(['email' => 'joe@example.com']);

        $response = $this->actingAs(create_admin())
            ->get(route('admin.users.show', $user));

        $response->assertStatus(Response::HTTP_OK)
            ->assertElementHasChild('body', 'turbo-frame[id="frame_update_email"]')
            ->assertElementHasChild('turbo-frame[id="frame_update_email"]', 'a[href="'.route('admin.user-emails.edit', $user).'"]');
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $user = create_user();

        $response = $this->actingAs(create_admin())
            ->from(route('admin.users.index'))
            ->delete(route('admin.users.destroy', $user));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status');

        $this->assertModelMissing($user);
    }

    /** @test */
    public function user_deleted_event_is_logged()
    {
        $this->assertCount(0, Activity::get());

        $user = create_user();
        $admin = create_admin();

        $this->actingAs($admin)
            ->from(route('admin.users.index'))
            ->delete(route('admin.users.destroy', $user));

        $this->assertCount(1, Activity::get());

        $activity = Activity::first();

        Config::set(['activitylog.subject_returns_soft_deleted_models' => true]);

        $this->assertSame(ActivityEvents::user_deleted->name, $activity->event);
        $this->assertTrue($activity->causer->is($admin));
        $this->assertTrue($activity->subject->is($user));
        $this->assertSame(['restore_url' => route('admin.users.restore', $user)], $activity->properties);
    }

    /** @test */
    public function user_cannot_delete_himself()
    {
        $admin = create_admin();

        $response = $this->actingAs($admin)
            ->from(route('admin.users.index'))
            ->delete(route('admin.users.destroy', $admin));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status');

        $this->assertModelExists($admin);

        $this->assertStringContainsString(session()->get('status')['message'], 'You cannot delete yourself.');
    }

    /** @test */
    public function not_found_is_thrown_if_user_do_not_exists()
    {
        $admin = create_admin();

        $this->withoutExceptionHandling();
        $this->expectException(ModelNotFoundException::class);

        // user with id 2 does not exist
        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', 2));
    }
}
