<?php

namespace Tests\Unit\Services;

use App\Services\AdminNav;
use PHPUnit\Framework\TestCase;

/** @see \App\Services\AdminNav */
class AdminNavTest extends TestCase
{
    /** @test */
    public function build()
    {
        $nav = new AdminNav;
        $actual = $nav->build();
        $expected = [
            ['svg' => 'svg.dashboard', 'route' => 'admin.dashboards.index', 'title' => 'Dashboard'],
            ['svg' => 'svg.users', 'route' => 'admin.users.index', 'title' => 'Users'],
        ];

        $this->assertSame($expected, $actual);
    }
}
