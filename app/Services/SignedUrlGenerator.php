<?php

namespace App\Services;

use Illuminate\Support\Facades\URL;

class SignedUrlGenerator
{
    /**
     * @param  \App\Models\User  $user
     * @param  string  $newEmail
     * @param  \Illuminate\Support\Carbon  $validUntil
     * @return string
     */
    public function forNewEmail($user, $newEmail, $validUntil)
    {
        return URL::temporarySignedRoute(
            'account.verification.verify',
            $validUntil,
            ['user' => $user->id, 'new_email' => $newEmail]
        );
    }
}
