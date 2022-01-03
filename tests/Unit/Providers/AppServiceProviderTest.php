<?php

namespace Tests\Unit\Providers;

use App\Providers\AppServiceProvider;
use Facades\Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Tests\TestCase;

/** @see \App\Providers\AppServiceProvider */
class AppServiceProviderTest extends TestCase
{
    /** @test */
    public function exclude_unvalidated_array_keys()
    {
        $app = app();
        $appServiceProvider = $app->makeWith(AppServiceProvider::class, ['app' => $app]);
        Validator::shouldReceive('excludeUnvalidatedArrayKeys')->once();

        // Password::shouldReceive('defaults')->once();
        // Password::shouldReceive('min')->once();

        //Model::shouldReceive('unguard')->once();

        $appServiceProvider->boot();
    }
}
