<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateEmailRequest;
use App\Notifications\EmailUpdateRequestNotification;
use App\Notifications\EmailUpdateWarningNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('account.email.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Account\UpdateEmailRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateEmailRequest $request)
    {
        Log::channel('security_log')
            ->info(
                'User with an id {user()->id}, requested email change from {user()->email} to {$request->new_email}.'
            );

        msg(
            "A email change request has been sent to {$request->new_email}.
            Please check your inbox for fuhrer instruction.
        );

        $request->user()->notify(new EmailUpdateRequestNotification(
            Carbon::now()->addDay(),
            $request->new_email
        ));

        $request->user()->notify(new EmailUpdateWarningNotification($request->new_email));

        return redirect()->route('account.profile');
    }
}
