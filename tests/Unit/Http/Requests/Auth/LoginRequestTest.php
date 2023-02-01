<?php

namespace Tests\Unit\Http\Requests\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\TestCase;

/** @see \App\Http\Requests\Auth\LoginRequest */
class LoginRequestTest extends TestCase
{
    use TestableFormRequest;

    /** @test */
    public function test_login_rules_pass()
    {
        $this->createFormRequest(LoginRequest::class)
            ->validate([
                'email' => 'joe@example.com',
                'password' => 'valid-password',
            ])
            ->assertPasses();
    }

    /**
     * @test
     *
     * @dataProvider validationFailsProvider
     */
    public function test_login_rules_fail($name, $value, $rule)
    {
        $this->createFormRequest(LoginRequest::class)
            ->validate([$name => $value])
            ->assertFails([$name => $rule]);
    }

    /** @test */
    public function request_is_allowed_for_all()
    {
        $request = new LoginRequest();
        $this->assertTrue($request->authorize());
    }

    public function validationFailsProvider()
    {
        return [
            'Test email is required' => ['email', '', 'required'],
            'Test email is string' => ['email', 123, 'string'],
            'Test email is valid email address' => ['email', 'not-valid-email-address', 'email'],
            'Test password is required' => ['password', '', 'required'],
            'Test password is string' => ['password', 123, 'string'],
        ];
    }
}
