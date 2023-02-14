<?php

namespace Tests\Feature\Http\Controllers\Account;

use Illuminate\Http\Response;
use Tests\TestCase;

/** @see \App\Http\Controllers\Account\ProfileController */
class ProfileControllerTest extends TestCase
{
    /** @test */
    public function auth_middleware_is_applied_to_the_view_request()
    {
        $this->get(route('accounts.profile'))
            ->assertMiddlewareIsApplied('auth');
    }

    /** @test */
    public function verified_middleware_is_applied_to_view()
    {
        $this->get(route('accounts.profile'))
            ->assertMiddlewareIsApplied('verified');
    }

    /** @test */
    public function profile_view_can_be_rendered()
    {
        $response = $this->actingAs(create_user())
            ->get(route('accounts.profile'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function turbo_frame_update_password_exists_with_a_link()
    {
        $response = $this->actingAs(create_user())
            ->get(route('accounts.profile'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertElementHasChild(
                'turbo-frame[id="frame_update_password"]',
                'a[href="'.route('accounts.passwords.edit').'"]'
            );
    }

    /** @test */
    public function turbo_frame_update_email_exists_with_a_link()
    {
        $response = $this->actingAs(create_user())
            ->get(route('accounts.profile'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertElementHasChild(
                'turbo-frame[id="frame_update_email"]',
                'a[href="'.route('accounts.emails.edit').'"]'
            );
    }
}
