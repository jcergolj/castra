<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return view('account.profile');
    }
}
