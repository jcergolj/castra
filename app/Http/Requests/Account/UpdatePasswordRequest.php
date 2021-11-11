<?php

namespace App\Http\Requests\Account;

use App\Rules\PasswordCheckRule;
use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
            'new_password' => [
                new PasswordRule($this->new_password_confirmation),
            ],
            'current_password' => [
                'required',
                new PasswordCheckRule($this->user()),
            ],
        ];
    }
}
