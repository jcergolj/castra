<?php

namespace Tests\Feature\Http\Controllers\Account;

use Tests\TestCase;
use App\Enums\ActivityEvents;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Services\SignedUrlGenerator;
use Tests\Concerns\TestableMiddleware;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/** @see \App\Http\Controllers\VerifyNewEmailController */
class VerifyNewEmailControllerTest extends TestCase
{
    use TestableMiddleware;

    /**
     * @test
     */
    public function throttle_is_applied_for_account_verification_verify_route()
    {
        $this->assertContains('throttle', $this->getMiddlewareFor('accounts.verification.verify'));
    }

    /** @test */
    public function abort_if_request_has_invalid_signature()
    {
        $this->withoutExceptionHandling();
        $this->expectException(HttpException::class);

        $this->get('invalid');
    }

    /** @test */
    public function abort_if_user_does_not_exists()
    {
        $this->withoutExceptionHandling();
        $this->expectException(ModelNotFoundException::class);

        $signedUrlGenerator = new SignedUrlGenerator();
        $url = $signedUrlGenerator->forNewEmail(make_user(), 'new.email@example.com', Carbon::now()->addDay());

        $this->get($url);
    }

    /** @test */
    public function new_email_is_verified()
    {
        $user = create_user(['email' => 'joe@example.com']);

        $signedUrlGenerator = new SignedUrlGenerator();
        $url = $signedUrlGenerator->forNewEmail($user, 'new.email@example.com', Carbon::now()->addDay());

        $response = $this->get($url);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('status')
            ->assertRedirect(route('login'));

        $this->assertSame('new.email@example.com', $user->fresh()->email);
    }

    /** @test */
    public function email_updated_by_user_event_is_logged()
    {
        $this->assertCount(0, Activity::get());

        $user = create_user(['email' => 'joe@example.com']);

        $signedUrlGenerator = new SignedUrlGenerator();
        $url = $signedUrlGenerator->forNewEmail($user, 'new.email@example.com', Carbon::now()->addDay());

        $this->get($url);

        $this->assertCount(1, Activity::get());

        $activity = Activity::first();

        $this->assertSame(ActivityEvents::email_updated_by_user->name, $activity->event);
        $this->assertTrue($activity->causer->is($user));
        $this->assertTrue($activity->subject->is($user));
        $this->assertTrue($activity->properties->contains('new.email@example.com'));
    }

    /** @test */
    public function confirm_user_email_if_authenticated()
    {
        $user = create_user(['email' => 'joe@example.com']);

        $signedUrlGenerator = new SignedUrlGenerator();
        $url = $signedUrlGenerator->forNewEmail($user, 'new.email@example.com', Carbon::now()->addDay());
        $response = $this->actingAs($user)
            ->get($url);

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('status')
            ->assertRedirect(route('login'));

        $this->assertSame('new.email@example.com', $user->fresh()->email);
    }
}
