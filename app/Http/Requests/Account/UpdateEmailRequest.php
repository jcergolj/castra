<?php

namespace App\Http\Requests\Account;

use App\Rules\PasswordCheckRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmailRequest extends FormRequest
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
            'new_email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],
            'current_password' => [
                'required',
                new PasswordCheckRule($this->user()),
            ],
        ];
    }
}
