<?php

namespace Tests\Feature\Http\Controllers\Admin\UserController;

use App\Enums\ActivityEvents;
use App\Models\Activity;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\TestCase;

/** @see \App\Http\Controllers\Admin\UserController::destroy() */
class DestroyTest extends TestCase
{
    use TestableFormRequest;

    /** @test */
    public function auth_middleware_is_applied_to_the_destory_request()
    {
        $this->delete(route('admin.users.destroy', create_user()))
            ->assertMiddlewareIsApplied('auth');
    }

    /** @test */
    public function admin_middleware_is_applied_to_the_destory_request()
    {
        $this->delete(route('admin.users.destroy', create_user()))
            ->assertMiddlewareIsApplied('admin');
    }

    /** @test */
    public function verified_middleware_is_applied_to_the_destory_request()
    {
        $this->delete(route('admin.users.destroy', create_user()))
            ->assertMiddlewareIsApplied('verified');
    }

    /** @test */
    public function delete_form_is_present_on_the_site()
    {
        $user = create_user(['email' => 'joe@example.com']);

        $response = $this->actingAs(create_admin())
            ->get(route('admin.users.index'));

        $response->assertViewHasForm('id="delete_user_'.$user->id.'"', 'delete', route('admin.users.destroy', $user))
            ->assertFormHasSubmitButton();
    }

    /** @test */
    public function admin_can_delete_user()
    {
        $user = create_user();

        $response = $this->actingAs(create_admin())
            ->from(route('admin.users.index', ['page' => 2]))
            ->delete(route('admin.users.destroy', $user));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('admin.users.index', ['page' => 1]))
            ->assertSessionHas('status');

        $this->assertSoftDeleted($user);
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

        $this->assertSame(ActivityEvents::Deleted, $activity->event);
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
