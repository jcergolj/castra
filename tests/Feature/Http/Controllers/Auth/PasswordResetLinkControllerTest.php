<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Requests\Auth\PasswordResetLinkRequest;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\TestCase;

/** @see \App\Http\Controllers\Auth\PasswordResetLinkController */
class PasswordResetLinkControllerTest extends TestCase
{
    use TestableFormRequest;

    public function test_reset_password_link_screen_can_be_rendered()
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHasForm('post', route('password.email'))
            ->assertFormHasCSRF()
            ->assertFormHasEmailInput('email')
            ->assertFormHasSubmitButton();
    }

    public function test_reset_password_link_can_be_requested()
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->from(route('password.request'))->post(route('password.email'), ['email' => $user->email]);

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
