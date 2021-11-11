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
use App\Http\Requests\Admin\StoreDeletedUserRequest;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/** @see \App\Http\Controllers\DeletedUserController */
class DeletedUserControllerTest extends TestCase
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
            'Admin middleware is not applied to admin.bulk.users.destroy.' => ['admin', 'admin.bulk.users.destroy'],
            'Auth middleware is not applied to admin.bulk.users.destroy.' => ['auth', 'admin.bulk.users.destroy'],
            'Verified middleware is not applied to admin.bulk.users.destroy.' => ['verified', 'admin.bulk.users.destroy'],
            'Admin middleware is not applied to admin.users.restore.' => ['admin', 'admin.users.restore'],
            'Auth middleware is not applied to admin.users.restore.' => ['auth', 'admin.users.restore'],
            'Verified middleware is not applied to admin.users.restore.' => ['verified', 'admin.users.restore'],
        ];
    }

    /** @test */
    public function admin_can_delete_users()
    {
        $joe = create_user(['email' => 'joe@example.com']);
        $jack = create_user(['email' => 'jack@example.com']);
        $jane = create_user(['email' => 'jane@example.com']);

        $response = $this->actingAs(create_admin())
            ->from(route('admin.users.index'))
            ->post(route('admin.bulk.users.destroy', [
                'ids' => [
                    $joe->id,
                    $jack->id
                ]
            ]));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status');

        $this->assertSoftDeleted($joe);
        $this->assertSoftDeleted($jack);

        $this->assertNotSoftDeleted($jane);
    }

    /** @test */
    public function user_deleted_event_is_logged()
    {
        $this->assertCount(0, Activity::get());

        $joe = create_user(['email' => 'joe@example.com']);
        $jack = create_user(['email' => 'jack@example.com']);

        $response = $this->actingAs($admin = create_admin())
            ->from(route('admin.users.index'))
            ->post(route('admin.bulk.users.destroy', [
                'ids' => [
                    $joe->id,
                    $jack->id
                ]
            ]));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status');

        $this->assertCount(2, Activity::get());

        $activities = Activity::get();

        Config::set(['activitylog.subject_returns_soft_deleted_models' => true]);

        $this->assertSame(ActivityEvents::user_deleted->name, $activities[0]->event);
        $this->assertTrue($activities[0]->causer->is($admin));
        $this->assertTrue($activities[0]->subject->is($joe));
        $this->assertSame(['restore_url' => route('admin.users.restore', $joe)], $activities[0]->properties);

        $this->assertSame(ActivityEvents::user_deleted->name, $activities[1]->event);
        $this->assertTrue($activities[1]->causer->is($admin));
        $this->assertTrue($activities[1]->subject->is($jack));
        $this->assertSame(['restore_url' => route('admin.users.restore', $jack)], $activities[1]->properties);
    }

    /** @test */
    public function store_has_store_deleted_user_request()
    {
        $this->assertRouteUsesFormRequest('admin.bulk.users.destroy', StoreDeletedUserRequest::class);
    }

    /** @test */
    function admin_can_restore_user()
    {
        $deletedUser = create_user(['deleted_at' => now()]);

        $response = $this->actingAs(create_admin())
            ->from(route('admin.users.index'))
            ->delete(route('admin.users.restore', $deletedUser));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status');

        $this->assertModelExists($deletedUser);
        $this->assertNotSoftDeleted($deletedUser);
    }
}
