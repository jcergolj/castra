<?php

namespace App\Services;

class AdminNav
{
    /** @return array */
    public function build()
    {
        return [
            ['svg' => 'svg.dashboard', 'route' => 'admin.dashboards.index', 'title' => 'Dashboard'],
            ['svg' => 'svg.users', 'route' => 'admin.users.index', 'title' => 'Users'],
            ['svg' => 'svg.log', 'route' => 'activities.index', 'title' => 'Log'],
        ];
    }
}
