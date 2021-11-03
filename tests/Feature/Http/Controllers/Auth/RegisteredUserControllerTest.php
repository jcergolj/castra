<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\TestCase;

/** @see \App\Http\Controllers\Auth\RegisteredUserController */
class RegisteredUserControllerTest extends TestCase
{
    use TestableFormRequest;

    public function setUp() : void
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function registration_view_can_be_rendered()
    {
        $response = $this->get(route('register'))
            ->assertViewHasForm(null, 'post', route('register.store'))
            ->assertFormHasCSRF()
            ->assertFormHasEmailInput('email')
            ->assertFormHasPasswordInput('password')
            ->assertFormHasPasswordInput('password_confirmation')
            ->assertFormHasSubmitButton();

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function new_user_can_register()
    {
        $this->assertCount(0, User::get());

        $response = $this->from(route('register'))
            ->post(route('register.store'), [
                'email' => 'joe@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertCount(1, User::get());

        $user = User::first();

        $this->assertSame('joe@example.com', $user->email);
        $this->assertTrue(Hash::check('password', $user->password));

        $this->assertAuthenticated();

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    /** @test */
    public function assert_registered_event_is_dispatched()
    {
        Event::assertNotDispatched(Registered::class);

        $this->post(route('register.store'), [
            'email' => 'joe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::first();

        Event::assertDispatched(Registered::class, function ($eventUser) use ($user) {
            return $eventUser->user->is($user);
        });
    }

    /** @test */
    public function controller_uses_login_form_request()
    {
        $this->assertRouteUsesFormRequest('register.store', RegisterUserRequest::class);
    }
}
