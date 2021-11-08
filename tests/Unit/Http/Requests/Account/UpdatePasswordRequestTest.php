<?php

namespace Tests\Unit\Http\Requests\Account;

use App\Http\Requests\Account\UpdatePasswordRequest;
use App\Rules\PasswordCheckRule;
use App\Rules\PasswordRule;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\TestCase;

/** @see \App\Http\Requests\Account\UpdatePasswordRequest */
class UpdatePasswordRequestTest extends TestCase
{
    use TestableFormRequest;

    /** @test */
    public function test_update_password_rules_pass()
    {
        $this->createFormRequest(UpdatePasswordRequest::class)
            ->by(create_user(['password' => bcrypt('password')]))
            ->validate([
                'current_password' => 'password',
                'new_password' => 'new_password',
                'new_password_confirmation' => 'new_password',
            ])
            ->assertPasses();
    }

    /** @test */
    public function current_password_is_required()
    {
        $this->createFormRequest(UpdatePasswordRequest::class)
            ->by(create_user(['password' => bcrypt('password')]))
            ->validate(['current_password' => ''])
            ->assertFails(['current_password' => 'required']);
    }

    /** @test */
    public function current_password_is_instance_of_password_check_class()
    {
        $this->createFormRequest(UpdatePasswordRequest::class)
            ->by(create_user(['password' => bcrypt('password')]))
            ->validate(['current_password' => 'invalid'])
            ->assertFails(['current_password' => PasswordCheckRule::class]);
    }

    /** @test */
    public function new_password_is_instance_of_password_rule_class()
    {
        $this->createFormRequest(UpdatePasswordRequest::class)
            ->by(create_user(['password' => bcrypt('password')]))
            ->validate(['current_password' => ''])
            ->assertFails(['current_password' => PasswordRule::class]);
    }
}
