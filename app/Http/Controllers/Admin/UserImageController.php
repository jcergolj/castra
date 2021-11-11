<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserImageController extends Controller
{
    /**
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('admin.users.edit-profile-image', ['user' => $user]);
    }

    /**
     * @param  \App\Http\Requests\Account\UpdateImageRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateImageRequest $request)
    {
    }
}
