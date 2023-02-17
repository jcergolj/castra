<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class SignedUrlGenerator
{
    public function forNewEmail(User $user, string $newEmail, Carbon $validUntil): string
    {
        return URL::temporarySignedRoute(
            'accounts.verification.verify',
            $validUntil,
            ['user' => $user->id, 'new_email' => $newEmail]
        );
    }
}
