<?php

namespace Tests\Feature\Http\Controllers\Account;

use App\Http\Requests\Account\UpdatePasswordRequest;
use App\Notifications\PasswordUpdatedNotification;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\Concerns\TestableMiddleware;
use Tests\TestCase;

/** @see \App\Http\Controllers\Account\PasswordController */
class PasswordControllerTest extends TestCase
{
    use TestableFormRequest, TestableMiddleware;

    public function setUp() : void
    {
        parent::setUp();

        Notification::fake();
    }

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
            'Auth middleware is not applied to account.password.edit route' => ['auth', 'account.password.edit'],
            'Auth middleware is not applied to account.password.update route' => ['auth', 'account.password.update'],
            'Verified middleware is not applied to account.password.edit route' => ['verified', 'account.password.edit'],
            'Verified middleware is not applied to account.password.update route' => ['verified', 'account.password.update'],
        ];
    }

    /** @test */
    public function password_edit_view_can_be_rendered()
    {
        $response = $this->actingAs(create_user())
            ->get(route('account.password.edit'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHasForm('id="update_password"', 'patch', route('account.password.update'))
            ->assertFormHasCSRF()
            ->assertFormHasPasswordInput('current_password')
            ->assertFormHasPasswordInput('new_password')
            ->assertFormHasPasswordInput('new_password_confirmation')
            ->assertFormHasSubmitButton();
    }

    /** @test */
    public function form_is_inside_turbo_frame()
    {
        $response = $this->actingAs(create_user())
            ->get(route('account.password.edit'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertElementHasChild(
                'turbo-frame[id="frame_update_password"]',
                'form[action="'.route('account.password.update').'"]'
            );
    }

    /** @test */
    public function user_can_change_his_password()
    {
        $jane = create_user(['password' => bcrypt('user-1-password')]);
        $user = create_user(['password' => bcrypt('password')]);
        $jack = create_user(['password' => bcrypt('user-3-password')]);

        $response = $this->actingAs($user)
            ->from(route('account.profile'))
            ->patch(route('account.password.update'), [
                'current_password' => 'password',
                'new_password' => 'new-password',
                'new_password_confirmation' => 'new-password',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('account.profile'))
            ->assertSessionHas('status');

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
        $this->assertTrue(Hash::check('user-1-password', $jane->fresh()->password));
        $this->assertTrue(Hash::check('user-3-password', $jack->fresh()->password));
    }

    /** @test */
    public function password_updated_notification_is_sent_to_the_user()
    {
        Notification::assertNothingSent(PasswordUpdatedNotification::class);

        $user = create_user(['password' => bcrypt('password')]);

        $this->actingAs($user)
            ->from(route('account.profile'))
            ->patch(route('account.password.update'), [
                'current_password' => 'password',
                'new_password' => 'new-password',
                'new_password_confirmation' => 'new-password',
            ]);

        Notification::assertSentTo($user, PasswordUpdatedNotification::class);
    }

    /** @test */
    public function password_controller_has_password_update_request()
    {
        $this->assertRouteUsesFormRequest('account.password.update', UpdatePasswordRequest::class);
    }
}
