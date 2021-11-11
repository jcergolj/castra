<?php

namespace App\Providers;

use App\Rules\PasswordRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /** @return void */
    public function register()
    {
    }

    /** @return void */
    public function boot()
    {
        Model::unguard();
        Password::defaults(function () {
            $rule = Password::min(PasswordRule::MIN_PASSWORD_LENGTH);

            return config('app.env') === 'production'
                ? $rule->mixedCase()->uncompromised()
                : $rule;
        });

        Blade::directive('turboReload', function ($expression) {
            return "<?php if(request()->header('Turbo-Frame') === null) { echo 'data-turbo-frame=\"_top\" target=\"_top\"'; } ?>";
        });

        Blade::directive('hasTurboFrameHeader', function ($expression) {
            return "<?php if(request()->header('Turbo-Frame') === null) { ?>";
        });

        Blade::directive('endHasTurboFrameHeader', function ($expression) {
            return '<?php } ?>';
        });
    }
}
