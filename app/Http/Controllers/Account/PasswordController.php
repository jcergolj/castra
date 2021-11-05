<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdatePasswordRequest;
use App\Notifications\PasswordUpdatedNotification;

class PasswordController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('account.password.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Account\UpdatePasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePasswordRequest $request)
    {
            $request->user()->update(['password' => bcrypt($request->new_password)]);

        $request->user()->notify(new PasswordUpdatedNotification());

        msg('Your password has been successfully updated.');

        return redirect()->route('account.profile');
    }
}
