<?php

namespace App\Http\Controllers\Account;

use App\Enums\ActivityEvents;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyNewEmailController extends Controller
{
    /**
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        auth()->logout();

        abort_if(! $request->hasValidSignature(), Response::HTTP_FORBIDDEN);

        /** @var \App\Models\User $user */
        $user = User::findOrFail($request->user);

        $user->update([
            'email' => $request->new_email,
        ]);

        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->event(ActivityEvents::email_updated_by_user->name)
            ->withProperties([
                'email' => $request->new_email,
            ])
            ->log(ActivityEvents::email_updated_by_user->name);

        msg('Your email has been successfully updated.');

        return redirect()->route('login');
    }
}
