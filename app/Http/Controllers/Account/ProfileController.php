<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /** @return \Illuminate\View\View */
    public function __invoke()
    {
        return view('accounts.profile');
    }
}
