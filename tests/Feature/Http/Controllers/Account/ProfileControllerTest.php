<?php

namespace Tests\Feature\Http\Controllers\Account;

use Illuminate\Http\Response;
use Tests\Concerns\TestableMiddleware;
use Tests\TestCase;

/** @see \App\Http\Controllers\Account\ProfileController */
class ProfileControllerTest extends TestCase
{
    use TestableMiddleware;

    /** @test */
    public function auth_middleware_is_applied_for_account_profile_route()
    {
        $this->assertContains('auth', $this->getMiddlewareFor('account.profile'));
    }

    /** @test */
    public function verified_middleware_is_applied_for_account_profile_route()
    {
        $this->assertContains('verified', $this->getMiddlewareFor('account.profile'));
    }

    /** @test */
    public function profile_view_can_be_rendered()
    {
        $response = $this->actingAs(create_user())
            ->get(route('account.profile'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function turbo_frame_update_password_exists_with_a_link()
    {
        $response = $this->actingAs(create_user())
            ->get(route('account.profile'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertElementHasChild(
                'turbo-frame[id="frame_update_password"]',
                'a[href="'.route('account.password.edit').'"]'
            );
    }

    /** @test */
    public function turbo_frame_update_email_exists_with_a_link()
    {
        $response = $this->actingAs(create_user())
            ->get(route('account.profile'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertElementHasChild(
                'turbo-frame[id="frame_update_email"]',
                'a[href="'.route('account.email.edit').'"]'
            );
    }
}
