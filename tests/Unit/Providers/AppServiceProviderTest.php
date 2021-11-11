<?php

namespace Tests\Unit\Providers;

use App\Providers\AppServiceProvider;
use App\Rules\PasswordRule;
use Illuminate\Database\Eloquent\Concerns\GuardsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Tests\FakePassword;
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
    public function assert_password_defaults_for_testing_env()
    {
        Config::set('app.env', 'testing');

        $app = app();
        $appServiceProvider = $app->makeWith(AppServiceProvider::class, ['app' => $app]);

        $appServiceProvider->boot();

        $this->assertFalse(FakePassword::getUncompromised());
        $this->assertSame(PasswordRule::MIN_PASSWORD_LENGTH, FakePassword::getMin());
        $this->assertFalse(FakePassword::getUncompromised());
    }

    /** @test */
    public function assert_password_defaults_for_production_env()
    {
        Config::set('app.env', 'production');

        $app = app();
        $appServiceProvider = $app->makeWith(AppServiceProvider::class, ['app' => $app]);

        $appServiceProvider->boot();

        $this->assertTrue(FakePassword::getUncompromised());
        $this->assertSame(PasswordRule::MIN_PASSWORD_LENGTH, FakePassword::getMin());
        $this->assertTrue(FakePassword::getUncompromised());
    }

    /** @test */
    public function models_are_unguarded()
    {
        $this->assertTrue(Model::isUnguarded());
    }
}
