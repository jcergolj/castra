<?php

namespace App\Http\Controllers\Account;

use App\Enums\ActivityEvents;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Activitylog\Models\Activity;

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

        $this->logActivity($request, $user);

        msg('Your email has been successfully updated.');

        return to_route('login');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return void
     */
    private function logActivity($request, $user)
    {
        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->event(ActivityEvents::email_updated_by_user->name)
            ->tap(function (Activity $activity) {
                $activity->event = ActivityEvents::email_updated_by_user;
            })
            ->withProperties([
                'email' => $request->new_email,
            ])
            ->log(ActivityEvents::email_updated_by_user->name);
    }
}
