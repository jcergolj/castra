<?php

namespace Tests\Unit\Rules;

use App\Rules\PasswordRule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Tests\TestCase;

/** @see \App\Rules\PasswordRule */
class PasswordRuleTest extends TestCase
{
    /** @test */
    public function validation_passes()
    {
        $rule = new PasswordRule('password');
        $this->assertTrue($rule->passes('password', 'password'));
    }

    /** @test */
    public function validation_passes_if_no_confirmation()
    {
        $rule = new PasswordRule();
        $this->assertTrue($rule->passes('password', 'password'));
    }

    /** @test */
    public function password_must_be_confirmed()
    {
        $rule = new PasswordRule('password'.'-not-confirmed');

        $this->assertFalse($rule->passes('password', 'password'));
        $this->assertArrayHasKey('Confirmed', $rule->failedRules['password']);
    }

    /** @test */
    public function password_is_required()
    {
        $rule = new PasswordRule('');

        $this->assertFalse($rule->passes('password', ''));
        $this->assertArrayHasKey('Required', $rule->failedRules['password']);
    }

    /** @test */
    public function password_must_be_at_least_x_char_in_length()
    {
        $password = Str::random(PasswordRule::MIN_PASSWORD_LENGTH - 1);
        $rule = new PasswordRule($password);

        $this->assertFalse($rule->passes('password', $password));
        $this->assertSame(
            trans(
                'validation.min.string',
                [
                    'attribute' => trans('validation.attributes.password'),
                    'min' => PasswordRule::MIN_PASSWORD_LENGTH,
                ]
            ),
            $rule->message()
        );
    }

    /** @test */
    public function password_must_be_uncompromised_in_production()
    {
        Config::set('app.env', 'production');

        $rule = new PasswordRule();
        $this->assertFalse($rule->passes('password', 'Password'));
        $this->assertSame('The given Password has appeared in a data leak. Please choose a different Password.', $rule->message());
    }

    /** @test */
    public function skip_uncompromised_rule_if_not_in_production()
    {
        Config::set('app.env', 'local');
        $rule = new PasswordRule();
        $rule->passes('password', 'Password');
        $this->assertTrue($rule->passes('password', 'Password'));
    }

    /** @test */
    public function password_must_be_have_mixed_char_in_production()
    {
        Config::set('app.env', 'production');

        $rule = new PasswordRule();
        $this->assertFalse($rule->passes('password', 'secret-pwd'));
        $this->assertSame('The Password must contain at least one uppercase and one lowercase letter.', $rule->message());
    }

    /** @test */
    public function skip_mixed_rule_if_not_in_production()
    {
        Config::set('app.env', 'local');

        $rule = new PasswordRule();
        $this->assertTrue($rule->passes('password', 'Secret-pwd'));
    }
}
