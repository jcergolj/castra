<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AppLayout extends Component
{
    /** @return \Illuminate\View\View */
    public function render()
    {
        return view('layouts.app');
    }
}
