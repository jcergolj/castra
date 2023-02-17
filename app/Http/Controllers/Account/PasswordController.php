<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdatePasswordRequest;
use App\Notifications\PasswordUpdatedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function edit(): View
    {
        return view('accounts.passwords.edit');
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update(['password' => bcrypt($request->new_password)]);

        $request->user()->notify(new PasswordUpdatedNotification());

        msg_success('Your password has been successfully updated.', 'update-password');

        return to_route('accounts.profile');
    }
}
