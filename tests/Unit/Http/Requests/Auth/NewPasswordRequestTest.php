<?php

namespace Tests\Unit\Http\Requests\Auth;

use App\Http\Requests\Auth\NewPasswordRequest;
use App\Rules\PasswordRule;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

/** @see \App\Http\Requests\Auth\NewPasswordRequest */
class NewPasswordRequestTest extends TestCase
{
    use TestableFormRequest;

    /** @test */
    public function test_rules_pass()
    {
        $this->createFormRequest(NewPasswordRequest::class)
            ->validate([
                'token' => 'token',
                'email' => 'joe@example.com',
                'password' => 'password10',
            ])
            ->assertPasses();
    }

    /**
     * @test
     * @dataProvider validationFailsProvider
     */
    public function test_rules_fail($name, $value, $rule)
    {
        $this->createFormRequest(NewPasswordRequest::class)
            ->validate([$name => $value])
            ->assertFails([$name => $rule]);
    }

    public function validationFailsProvider()
    {
        return [
            'Test token is required' => ['token', '', 'required'],
            'Test email is required' => ['email', '', 'required'],
            'Test email is valid email address' => ['email', 'not-valid-email-address', 'email'],
        ];
    }

    /** @test */
    public function password_rule_is_instance_of_password_rule_class()
    {
        Assert::assertTrue(
            $this->createFormRequest(NewPasswordRequest::class)->validator()->getRules()['password'][0] instanceof PasswordRule,
            'Password does not have password rule class'
        );
    }

    /** @test */
    public function request_is_allowed_for_all()
    {
        $request = new NewPasswordRequest();
        $this->assertTrue($request->authorize());
    }
}
