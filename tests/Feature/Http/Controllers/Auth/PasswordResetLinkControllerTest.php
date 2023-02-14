<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Requests\Auth\PasswordResetLinkRequest;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\TestCase;

/** @see \App\Http\Controllers\Auth\PasswordResetLinkController */
class PasswordResetLinkControllerTest extends TestCase
{
    use TestableFormRequest;

    /** @test */
    public function guest_middleware_is_applied_to_the_password_request_request()
    {
        $this->get(route('password.request'))
            ->assertMiddlewareIsApplied('guest');
    }

    /** @test */
    public function guest_middleware_is_applied_to_the_password_email_request()
    {
        $this->get(route('password.email'))
            ->assertMiddlewareIsApplied('guest');
    }

    public function test_reset_password_link_view_can_be_rendered()
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHasForm(null, 'post', route('password.email'))
            ->assertFormHasCSRF()
            ->assertFormHasEmailInput('email')
            ->assertFormHasSubmitButton();
    }

    public function test_reset_password_link_can_be_requested()
    {
        Notification::fake();

        $user = create_user();

        $response = $this->from(route('password.request'))
            ->post(route('password.email'), ['email' => $user->email]);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('password.request'))
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    /** @test */
    public function controller_uses_password_request_link_request()
    {
        $this->assertRouteUsesFormRequest('password.email', PasswordResetLinkRequest::class);
    }
}
