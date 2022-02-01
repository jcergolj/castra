<?php

namespace Tests\Unit\Http\Requests\Account;

use App\Http\Requests\Account\UpdateEmailRequest;
use App\Rules\PasswordCheckRule;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\TestCase;

/** @see \App\Http\Requests\Account\UpdateEmailRequest */
class UpdateEmailRequestTest extends TestCase
{
    use TestableFormRequest;

    /** @test */
    public function test_update_email_rules_pass()
    {
        $this->createFormRequest(UpdateEmailRequest::class)
            ->by(create_user(['password' => bcrypt('password')]))
            ->validate([
                'current_password' => 'password',
                'new_email' => 'new.email@example.com',
            ])
            ->assertPasses();
    }

    /** @test */
    public function current_password_is_required()
    {
        $this->createFormRequest(UpdateEmailRequest::class)
            ->by(create_user(['password' => bcrypt('password')]))
            ->validate(['current_password' => ''])
            ->assertFails(['current_password' => 'required']);
    }

    /** @test */
    public function current_password_is_instance_of_password_check_class()
    {
        $this->createFormRequest(UpdateEmailRequest::class)
            ->by(create_user(['password' => bcrypt('password')]))
            ->validate(['current_password' => 'invalid'])
            ->assertFails(['current_password' => PasswordCheckRule::class]);
    }

    /** @test */
    public function new_email_is_required()
    {
        $this->createFormRequest(UpdateEmailRequest::class)
            ->by(create_user())
            ->validate(['new_email' => ''])
            ->assertFails(['new_email' => 'required']);
    }

    /** @test */
    public function new_email_is_must_be_valid_email_address()
    {
        $this->createFormRequest(UpdateEmailRequest::class)
            ->by(create_user())
            ->validate(['new_email' => 'invalid'])
            ->assertFails(['new_email' => 'email']);
    }

    /** @test */
    public function new_email_is_must_be_unique()
    {
        create_user(['email' => 'joe@example.com']);

        $this->createFormRequest(UpdateEmailRequest::class)
            ->by(create_user())
            ->validate(['new_email' => 'joe@example.com'])
            ->assertFails(['new_email' => 'unique']);
    }
}
