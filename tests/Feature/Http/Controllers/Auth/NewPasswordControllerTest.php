<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Requests\Auth\NewPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\TestCase;

/** @see \App\Http\Controllers\Auth\NewPasswordController */
class NewPasswordControllerTest extends TestCase
{
    use TestableFormRequest;

    public function test_reset_password_view_can_be_rendered()
    {
        $this->get(route('password.reset', 'token'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewHasForm(null, 'post', route('password.update'))
            ->assertFormHasCSRF()
            ->assertFormHasHiddenInput('token', 'token')
            ->assertFormHasPasswordInput('password')
            ->assertFormHasPasswordInput('password_confirmation')
            ->assertFormHasSubmitButton();
    }

    public function test_password_can_be_reset_with_valid_token()
    {
        Event::fake();
        Notification::fake();

        $user = create_user();

        $this->post(route('password.email'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            Event::assertNotDispatched(PasswordReset::class);

            $response = $this->post(route('password.update'), [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertStatus(Response::HTTP_FOUND)
                ->assertRedirect(route('login'))
                ->assertSessionHasNoErrors()
                ->assertSessionHas('status');

            Event::assertDispatched(PasswordReset::class, function ($eventUser) use ($user) {
                return $eventUser->user->is($user);
            });

            return true;
        });
    }

    public function test_password_cannot_be_reset_with_invalid_token()
    {
        $user = create_user(['password' => Hash::make('password')]);

        $response = $this->from(route('password.reset', 'token'))
            ->post(route('password.update'), [
                'token' => 'invalid-token',
                'email' => $user->email,
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('password.reset', 'token'))
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function controller_uses_login_form_request()
    {
        $this->assertRouteUsesFormRequest('password.update', NewPasswordRequest::class);
    }
}
