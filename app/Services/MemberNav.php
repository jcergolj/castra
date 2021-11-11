<?php

namespace App\Services;

class MemberNav
{
    /** @return array */
    public function build()
    {
        return [
            ['svg' => 'svg.dashboard', 'route' => 'dashboards.index', 'title' => 'Dashboard'],
        ];
    }
}
