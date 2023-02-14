<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\TestCase;

/** @see \App\Http\Controllers\Auth\VerifyEmailControllerTest */
class VerifyEmailControllerTest extends TestCase
{
    use TestableFormRequest;

    /** @test */
    public function auth_middleware_is_applied_to_the_verification_verify_request()
    {
        $this->get(route('verification.verify', [1, 'hash']))
            ->assertMiddlewareIsApplied('auth');
    }

    /** @test */
    public function signed_middleware_is_applied_to_the_verification_verify_request()
    {
        $this->get(route('verification.verify', [1, 'hash']))
            ->assertMiddlewareIsApplied('signed');
    }

    /** @test */
    public function throttle_middleware_is_applied_to_the_verification_verify_request()
    {
        $this->get(route('verification.verify', [1, 'hash']))
            ->assertMiddlewareIsApplied('throttle:6,1');
    }

    /** @test */
    public function email_can_be_verified()
    {
        Event::fake();

        Event::assertNotDispatched(Verified::class);

        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(RouteServiceProvider::HOME.'?verified=1');

        Event::assertDispatched(Verified::class, function ($eventUser) use ($user) {
            return $eventUser->user->is($user);
        });

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function email_is_not_verified_with_invalid_hash()
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function already_verified_user_is_redirected_to_home_url()
    {
        $user = User::factory()->create([
            'email_verified_at' => Carbon::yesterday(),
        ]);

        $verifiedAt = $user->email_verified_at;

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $this->assertTrue($user->fresh()->email_verified_at->eq($verifiedAt));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(RouteServiceProvider::HOME.'?verified=1');
    }

    /** @test */
    public function controller_uses_email_verification_request()
    {
        $this->assertRouteUsesFormRequest('verification.verify', EmailVerificationRequest::class);
    }
}
