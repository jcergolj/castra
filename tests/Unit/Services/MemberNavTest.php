<?php

namespace Tests\Unit\Services;

use App\Services\MemberNav;
use PHPUnit\Framework\TestCase;

/** @see \App\Services\MemberNav */
class MemberNavTest extends TestCase
{
    /** @test */
    public function build()
    {
        $nav = new MemberNav;
        $actual = $nav->build();
        $expected = [
            ['svg' => 'svg.dashboard', 'route' => 'dashboards.index', 'title' => 'Dashboard'],
        ];

        $this->assertSame(sort($expected), sort($actual));
    }
}
