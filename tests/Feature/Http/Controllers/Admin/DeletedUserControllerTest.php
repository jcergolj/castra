<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreDeletedUserRequest;
use Illuminate\Http\Response;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\Concerns\TestableMiddleware;
use Tests\TestCase;

/** @see \App\Http\Controllers\DeletedUserController */
class DeletedUserControllerTest extends TestCase
{
    use TestableFormRequest, TestableMiddleware;

    /**
     * @test
     *
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
                    $jack->id,
                ],
            ]));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('status');

        $this->assertSoftDeleted($joe);
        $this->assertSoftDeleted($jack);

        $this->assertNotSoftDeleted($jane);
    }

    /** @test */
    public function store_has_store_deleted_user_request()
    {
        $this->assertRouteUsesFormRequest('admin.bulk.users.destroy', StoreDeletedUserRequest::class);
    }

    /** @test */
    public function admin_can_restore_user()
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
