<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Jcergolj\FormRequestAssertions\TestableFormRequest;
use Tests\Concerns\TestableMiddleware;
use Tests\TestCase;

/** @see \App\Http\Controllers\Admin\UserImageController */
class UserImageControllerTest extends TestCase
{
    use TestableFormRequest, TestableMiddleware;

    /**
     * @test
     *
     * @dataProvider middlewareRouteDataProvider
     */
    public function middleware_is_applied_for_routes($middleware, $route)
    {
        $this->assertContains($middleware, $this->getMiddlewareFor($route));
    }

    public function middlewareRouteDataProvider()
    {
        return [
            'Admin middleware is not applied to admin.user-images.edit.' => ['admin', 'admin.user-images.edit'],
            'Auth middleware is not applied to admin.user-images.edit.' => ['auth', 'admin.user-images.edit'],
            'Verified middleware is not applied to admin.user-images.edit.' => ['verified', 'admin.user-images.edit'],
            'Admin middleware is not applied to admin.user-images.update.' => ['admin', 'admin.user-images.update'],
            'Auth middleware is not applied to admin.user-images.update.' => ['auth', 'admin.user-images.update'],
            'Verified middleware is not applied to admin.user-images.update.' => ['verified', 'admin.user-images.update'],
        ];
    }
}
