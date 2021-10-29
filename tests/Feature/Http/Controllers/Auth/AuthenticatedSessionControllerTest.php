<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Response;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\TestCase;

/** @see \App\Http\Controllers\Auth\AuthenticatedSessionController */
class AuthenticatedSessionControllerTest extends TestCase
{
    use TestableFormRequest;

    /** @test */
    public function login_view_can_be_rendered()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHasForm(null, 'post', route('login.store'))
            ->assertFormHasCSRF()
            ->assertFormHasEmailInput('email')
            ->assertFormHasPasswordInput('password')
            ->assertFormHasCheckboxInput('remember')
            ->assertFormHasSubmitButton();
    }

    /** @test */
    public function users_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create();

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    /** @test */
    public function users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    /** @test */
    public function controller_uses_login_form_request()
    {
        $this->assertRouteUsesFormRequest('login.store', LoginRequest::class);
    }

    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = User::factory()->make([
            'email' => 'joe@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        $response = $this->delete(route('logout'));
        $response->assertRedirect('/');

        $this->assertGuest();
    }
}
