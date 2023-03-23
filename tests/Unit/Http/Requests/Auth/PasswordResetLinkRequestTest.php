<?php

namespace Tests\Unit\Http\Requests\Auth;

use App\Http\Requests\Auth\PasswordResetLinkRequest;
use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\TestCase;

/** @see \App\Http\Requests\Auth\PasswordResetLinkRequest */
class PasswordResetLinkRequestTest extends TestCase
{
    use TestableFormRequest;

    /** @test */
    public function test_rules_pass()
    {
        $this->createFormRequest(PasswordResetLinkRequest::class)
            ->validate([
                'email' => 'joe@example.com',
            ])
            ->assertPasses();
    }

    /**
     * @test
     *
     * @dataProvider validationFailsProvider
     */
    public function test_rules_fail($name, $value, $rule)
    {
        $this->createFormRequest(PasswordResetLinkRequest::class)
            ->validate([$name => $value])
            ->assertFails([$name => $rule]);
    }

    public static function validationFailsProvider()
    {
        return [
            'Test email is required' => ['email', '', 'required'],
            'Test email is valid email address' => ['email', 'not-valid-email-address', 'email'],
        ];
    }

    /** @test */
    public function request_is_allowed_for_all()
    {
        $request = new PasswordResetLinkRequest();
        $this->assertTrue($request->authorize());
    }
}
