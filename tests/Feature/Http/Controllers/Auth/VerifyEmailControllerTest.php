<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Tests\Concerns\TestableMiddleware;
use App\Providers\RouteServiceProvider;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/** @see \App\Http\Controllers\Auth\VerifyEmailControllerTest */
class VerifyEmailControllerTest extends TestCase
{
    use TestableFormRequest, TestableMiddleware;

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
            'Auth middleware is not applied to verification.verify' => ['auth', 'verification.verify'],
            'Signed middleware is not applied to verification.verify' => ['signed', 'verification.verify'],
            'Throttle middleware is not applied to verification.verify' => ['throttle', 'verification.verify'],
        ];
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
