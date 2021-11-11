<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdatePasswordRequest;
use App\Notifications\PasswordUpdatedNotification;

class PasswordController extends Controller
{
    /** @return \Illuminate\View\View */
    public function edit()
    {
        return view('accounts.passwords.edit');
    }

    /**
     * @param  \App\Http\Requests\Account\UpdatePasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePasswordRequest $request)
    {
        $request->user()->update(['password' => bcrypt($request->new_password)]);

        $request->user()->notify(new PasswordUpdatedNotification());

        msg_success('Your password has been successfully updated.', 'update-password');

        return redirect()->route('accounts.profile');
    }
}
