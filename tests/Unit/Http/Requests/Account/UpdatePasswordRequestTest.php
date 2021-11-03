<?php

namespace Tests\Unit\Http\Requests\Account;

use Tests\TestCase;
use PHPUnit\Framework\Assert;
use App\Http\Requests\Account\UpdatePasswordRequest;
use App\Rules\PasswordCheckRule;
use App\Rules\PasswordRule;
use Jcergolj\FormRequestAssertions\TestableFormRequest;

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

    /**
     * @test
     */
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
        Assert::assertTrue(
            $this->createFormRequest(UpdatePasswordRequest::class)
                ->by(create_user(['password' => bcrypt('password')]))
                ->validator()
                ->getRules()['current_password'][1] instanceof PasswordCheckRule,
            'Current password does not have password check rule class'
        );
    }

    /** @test */
    public function new_password_is_instance_of_password_rule_class()
    {
        Assert::assertTrue(
            $this->createFormRequest(UpdatePasswordRequest::class)
                ->by(create_user(['password' => bcrypt('password')]))
                ->validator()
                ->getRules()['new_password'][0] instanceof PasswordRule,
            'New password does not have password rule class'
        );
    }
}
