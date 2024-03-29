<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Request;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, LazilyRefreshDatabase;

    /** @return \Illuminate\Http\Request $request */
    public function makeRequestWith(User $user)
    {
        $request = $this->app->make(Request::class);

        return $request->setUserResolver(function () use ($user) {
            return $user;
        });
    }
}
