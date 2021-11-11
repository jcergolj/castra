<?php

namespace App\Providers;

use App\Rules\PasswordRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Tests\FakePassword;

class AppServiceProvider extends ServiceProvider
{
    public const PER_PAGE = 10;

    /** @return void */
    public function register()
    {
        if (config('app.env') === 'testing') {
            app()->extend(Password::class, function ($password, $app) {
                return new FakePassword($password);
            });
        }
    }

    /** @return void */
    public function boot()
    {
        Validator::excludeUnvalidatedArrayKeys();

        Model::unguard();

        Password::defaults(function () {
            $rule = Password::min(PasswordRule::MIN_PASSWORD_LENGTH);

            return config('app.env') === 'production'
                ? $rule->mixedCase()->uncompromised()
                : $rule;
        });
    }
}
