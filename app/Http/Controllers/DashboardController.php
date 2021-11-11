<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    /** @return \Illuminate\View\View */
    public function index()
    {
        return view('dashboards.index');
    }
}
