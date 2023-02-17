<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Blade::directive('turboReload', function () {
            return "<?php if(request()->header('Turbo-Frame') === null)
                { echo 'data-turbo-frame=\"_top\" target=\"_top\"'; } ?>";
        });

        Blade::directive('hasTurboFrameHeader', function () {
            return "<?php if(request()->header('Turbo-Frame') === null) { ?>";
        });

        Blade::directive('endHasTurboFrameHeader', function () {
            return '<?php } ?>';
        });
    }
}
