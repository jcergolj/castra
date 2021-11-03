<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

/** @see \App\Http\Controllers\Auth\EmailVerificationNotificationController */
class EmailVerificationNotificationControllerTest extends TestCase
{
    /** @test */
    public function email_verification_with_resend_verification_view_can_be_rendered_if_user_is_not_verified()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHasForm('id="resend_verification"', 'post', route('verification.send'))
            ->assertFormHasCSRF()
            ->assertFormHasSubmitButton();
    }

    /** @test */
    public function already_verified_user_is_redirected_to_home_url()
    {
        $user = User::factory()->create([
            'email_verified_at' => Carbon::yesterday(),
        ]);

        $verifiedAt = $user->email_verified_at;

        $response = $this->actingAs($user)->post(route('verification.send'));

        $this->assertTrue($user->fresh()->email_verified_at->eq($verifiedAt));

        $response->assertStatus(Response::HTTP_FOUND)
             ->assertRedirect(RouteServiceProvider::HOME);
    }

    /** @test */
    public function a_notification_is_sent_to_the_user()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)
            ->from(route('verification.notice'))
            ->post(route('verification.send'));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHas('status')
            ->assertRedirect(route('verification.notice'));

        Notification::assertSentTo($user, VerifyEmail::class);
    }
}
