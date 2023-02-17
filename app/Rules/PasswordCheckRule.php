<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class PasswordCheckRule implements Rule
{
    public function __construct(protected User $user)
    {
    }

    public function passes(mixed $attribute, mixed $password): bool
    {
        return Hash::check($password, $this->user->password);
    }

    public function message(): string
    {
        return trans('validation.password_check');
    }
}
