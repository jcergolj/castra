<?php

namespace App\Services;

class MemberNav
{
    /** @return array */
    public function build()
    {
        return [
            ['svg' => 'svg.dashboard', 'route' => 'dashboard.index', 'title' => 'Dashboard'],
        ];
    }
}
