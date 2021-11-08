<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyNewEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
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

        msg('Your email has been successfully update.');

        return redirect()->route('login');
    }
}
