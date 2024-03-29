<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/** @see \App\Http\Controllers\Auth\EmailVerificationPromptController */
class EmailVerificationPromptControllerTest extends TestCase
{
    /** @test */
    public function auth_middleware_is_applied_to_the_verification_notice_request()
    {
        $this->get(route('verification.notice'))
            ->assertMiddlewareIsApplied('auth');
    }

    /** @test */
    public function email_verification_view_can_be_rendered_if_user_is_not_verified()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)
            ->get(route('verification.notice'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHasForm('id="resend_verification"', 'post', route('verification.send'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewHasForm('id="logout"', 'delete', route('logout'));
    }

    /** @test */
    public function already_verified_user_is_redirected_to_home_url()
    {
        $user = User::factory()->create([
            'email_verified_at' => Carbon::now(),
        ]);

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(RouteServiceProvider::HOME);
    }
}
