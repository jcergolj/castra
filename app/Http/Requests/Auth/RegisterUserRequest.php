<?php

namespace App\Http\Requests\Auth;

use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /** @return bool */
    public function authorize()
    {
        return true;
    }

    /** @return array */
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [new PasswordRule($this->password_confirmation)],
        ];
    }
}
