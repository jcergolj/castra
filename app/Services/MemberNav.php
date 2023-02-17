<?php

namespace App\Services;

class MemberNav
{
    public function build(): array
    {
        return [
            ['svg' => 'svg.dashboard', 'route' => 'dashboards.index', 'title' => 'Dashboard'],
            ['svg' => 'svg.log', 'route' => 'activities.index', 'title' => 'Log'],
        ];
    }
}
