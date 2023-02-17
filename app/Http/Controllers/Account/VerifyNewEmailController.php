<?php

namespace App\Http\Controllers\Account;

use App\Enums\ActivityEvents;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyNewEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        auth()->logout();

        abort_if(! $request->hasValidSignature(), Response::HTTP_FORBIDDEN);

        /** @var \App\Models\User $user */
        $user = User::findOrFail($request->user);

        $user->update([
            'email' => $request->new_email,
        ]);

        $this->logActivity($request, $user);

        msg('Your email has been successfully updated.');

        return to_route('login');
    }

    private function logActivity(Request $request, User $user): void
    {
        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->event(ActivityEvents::EmailUpdatedByUser->value)
            ->withProperties([
                'email' => $request->new_email,
            ])
            ->log(ActivityEvents::EmailUpdatedByUser->value);
    }
}
