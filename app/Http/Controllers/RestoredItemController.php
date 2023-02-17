<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RestoredItemController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // if event not deleted abort
        // only admin or casuer owner can do it
    }
}
