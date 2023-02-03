<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestoredItemController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // if event not deleted abort
        // only admin or casuer owner can do it
    }
}
