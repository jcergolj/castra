<?php

namespace Tests\Feature\Http\Controllers\Account;

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Models\User;

/** @see \App\Http\Controllers\Account\ProfileController */
class ProfileControllerTest extends TestCase
{
    /** @test */
    public function profile_view_can_be_rendered()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('account.profile'));

        $response->assertStatus(Response::HTTP_OK);
            // ->assertViewHasForm(null, 'patch', route('account.password.update'))
            // ->assertFormHasCSRF()
            // ->assertFormHasEmailInput('email')
            // ->assertFormHasPasswordInput('password')
            // ->assertFormHasCheckboxInput('remember')
            // ->assertFormHasSubmitButton();
    }
}
