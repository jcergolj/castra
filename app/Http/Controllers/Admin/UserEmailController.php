<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserEmailController extends Controller
{
    /**
     * @param  \App\Models\User  $user  $
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $user)
    {
    }
}
