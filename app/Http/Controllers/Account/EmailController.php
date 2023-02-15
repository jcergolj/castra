<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateEmailRequest;
use App\Notifications\EmailUpdateRequestNotification;
use App\Notifications\EmailUpdateWarningNotification;
use Illuminate\Support\Carbon;

class EmailController extends Controller
{
    /** @return \Illuminate\View\View */
    public function edit()
    {
        return view('accounts.emails.edit');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateEmailRequest $request)
    {
        msg_success(
            "A email change request has been sent to {$request->new_email}.
            Please check your inbox for fuhrer instruction.",
            'update-email'
        );

        $request->user()?->notify(new EmailUpdateRequestNotification(
            Carbon::now()->addDay(),
            $request->new_email
        ));

        $request->user()?->notify(new EmailUpdateWarningNotification($request->new_email));

        return to_route('accounts.profile');
    }
}
