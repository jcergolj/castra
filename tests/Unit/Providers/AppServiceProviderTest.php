<?php

namespace Tests\Unit\Providers;

use App\Providers\AppServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
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

        $appServiceProvider->boot();
    }

    /** @test */
    public function models_are_unguarded()
    {
        $this->assertTrue(Model::isUnguarded());
    }
}
