<?php

namespace Tests\Feature\Http\Controllers\Account;

use Tests\TestCase;
use Illuminate\Http\Response;

/** @see \App\Http\Controllers\Account\ProfileController */
class ProfileControllerTest extends TestCase
{
    /** @test */
    public function profile_view_can_be_rendered()
    {
        $response = $this->actingAs(create_user())
            ->get(route('account.profile'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function turbo_frame_change_password_exists_with_a_link()
    {
        $response = $this->actingAs(create_user())
            ->get(route('account.profile'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertElementHasChild(
                'turbo-frame[id="change_password"]',
                'a[href="'.route('account.password.edit').'"]'
            );
    }
}
