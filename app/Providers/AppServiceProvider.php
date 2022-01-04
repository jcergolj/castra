<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

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
        Validator::excludeUnvalidatedArrayKeys();

        Model::unguard();
    }
}
