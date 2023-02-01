<?php

namespace Tests\Unit\Http\Requests\Auth;

use App\Http\Requests\Auth\RegisterUserRequest;
use App\Rules\PasswordRule;
use Illuminate\Support\Str;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\TestCase;

/** @see \App\Http\Requests\Auth\RegisterUserRequest */
class RegisterUserRequestTest extends TestCase
{
    use TestableFormRequest;

    /** @test */
    public function test_register_user_rules_pass()
    {
        $this->createFormRequest(RegisterUserRequest::class)
            ->validate([
                'email' => 'joe@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ])
            ->assertPasses();
    }

    /**
     * @test
     *
     * @dataProvider validationFailsProvider
     */
    public function test_register_user_rules_fail($name, $value, $rule)
    {
        $this->createFormRequest(RegisterUserRequest::class)
            ->validate([$name => $value])
            ->assertFails([$name => $rule]);
    }

    /** @test */
    public function user_must_be_unique()
    {
        create_user(['email' => 'joe@example.com']);

        $this->createFormRequest(RegisterUserRequest::class)
            ->validate(['email' => 'joe@example.com'])
            ->assertFails(['email' => 'unique']);
    }

    /** @test */
    public function request_is_allowed_for_all()
    {
        $request = new RegisterUserRequest();
        $this->assertTrue($request->authorize());
    }

    public function validationFailsProvider()
    {
        return [
            'Test email is required' => ['email', '', 'required'],
            'Test email must be a string' => ['email', 123, 'string'],
            'Test email must be a valid email address' => ['email', 'not-valid-email-address', 'email'],
            'Test email is less than 255 char in length' => ['email', Str::random(250).'@example.com', 'max:255'],
            'Test password is not instance of password rule class' => ['password', '', PasswordRule::class],
        ];
    }
}
