<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /** @return \Illuminate\View\View */
    public function index()
    {
        return view('admin.dashboards.index');
    }
}
