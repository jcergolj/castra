<?php

namespace App\Services;

class AdminNav
{
    /** @return array */
    public function build()
    {
        return [
            ['svg' => 'svg.dashboard', 'route' => 'admin.dashboard.index', 'title' => 'Dashboard'],
            ['svg' => 'svg.users', 'route' => 'admin.users.index', 'title' => 'Users'],
        ];
    }
}
