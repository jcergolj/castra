<?php

namespace App\Providers;

use App\Rules\PasswordRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public const PER_PAGE = 10;

    /** @return void */
    public function register()
    {
    }

    /** @return void */
    public function boot()
    {
        $this->app->bind(Password::class, function ($app) {
            return new Password(PasswordRule::MIN_PASSWORD_LENGTH);
        });

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
