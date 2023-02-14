<?php

namespace Tests\Feature\Http\Controllers\Account;

use App\Http\Requests\Account\UpdatePasswordRequest;
use App\Notifications\PasswordUpdatedNotification;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\TestCase;

/** @see \App\Http\Controllers\Account\PasswordController */
class PasswordControllerTest extends TestCase
{
    use TestableFormRequest;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    /** @test */
    public function auth_middleware_is_applied_to_the_edit_request()
    {
        $this->get(route('accounts.passwords.edit'))
            ->assertMiddlewareIsApplied('auth');
    }

    /** @test */
    public function verified_middleware_is_applied_to_the_edit_request()
    {
        $this->get(route('accounts.passwords.edit'))
            ->assertMiddlewareIsApplied('verified');
    }

    /** @test */
    public function password_edit_view_can_be_rendered()
    {
        $response = $this->actingAs(create_user())
            ->get(route('accounts.passwords.edit'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHasForm('id="update_password"', 'patch', route('accounts.passwords.update'))
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
            ->get(route('accounts.passwords.edit'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertElementHasChild(
                'turbo-frame[id="frame_update_password"]',
                'form[action="'.route('accounts.passwords.update').'"]'
            );
    }

    /** @test */
    public function auth_middleware_is_applied_to_the_update_request()
    {
        $this->patch(route('accounts.passwords.update'))
            ->assertMiddlewareIsApplied('auth');
    }

    /** @test */
    public function verified_middleware_is_applied_to_the_update_request()
    {
        $this->patch(route('accounts.passwords.update'))
            ->assertMiddlewareIsApplied('verified');
    }

    /** @test */
    public function user_can_change_his_password()
    {
        $jane = create_user(['password' => bcrypt('user-1-password')]);
        $user = create_user(['password' => bcrypt('password')]);
        $jack = create_user(['password' => bcrypt('user-3-password')]);

        $response = $this->actingAs($user)
            ->from(route('accounts.profile'))
            ->patch(route('accounts.passwords.update'), [
                'current_password' => 'password',
                'new_password' => 'new-password',
                'new_password_confirmation' => 'new-password',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('accounts.profile'))
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
            ->from(route('accounts.profile'))
            ->patch(route('accounts.passwords.update'), [
                'current_password' => 'password',
                'new_password' => 'new-password',
                'new_password_confirmation' => 'new-password',
            ]);

        Notification::assertSentTo($user, PasswordUpdatedNotification::class);
    }

    /** @test */
    public function password_controller_has_password_update_request()
    {
        $this->assertRouteUsesFormRequest('accounts.passwords.update', UpdatePasswordRequest::class);
    }
}
