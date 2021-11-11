<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class PasswordCheckRule implements Rule
{
    /** @var \App\Models\User */
    protected $user;

    /**
     * @param \App\Models\User  $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param  string  $attribute
     * @param  mixed  $password
     * @return bool
     */
    public function passes($attribute, $password)
    {
        return Hash::check($password, $this->user->password);
    }

    /**  @return string */
    public function message()
    {
        return trans('validation.password_check');
    }
}
