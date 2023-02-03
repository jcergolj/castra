<?php

namespace Tests\Feature\Http\Controllers\Account;

use Tests\TestCase;
use App\Models\User;
use App\Models\Activity;
use App\Enums\ActivityEvents;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Services\SignedUrlGenerator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/** @see \App\Http\Controllers\Account\VerifyNewEmailController */
class VerifyNewEmailControllerTest extends TestCase
{
    /** @test */
    function throttle_middleware_is_applied_to_the_edit_request()
    {
        $this->get(route('accounts.verification.verify'))
            ->assertMiddlewareIsApplied('throttle:6,1');
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

        $this->assertEquals(ActivityEvents::EmailUpdatedByUser, $activity->event);

        $this->assertTrue($activity->causer->is($user));
        $this->assertSame($user->id, $activity->subject_id);
        $this->assertSame(User::class, $activity->subject_type);
        $this->assertSame('new.email@example.com', $activity->properties['email']);
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
