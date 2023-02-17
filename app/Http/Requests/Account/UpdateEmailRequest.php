<?php

namespace App\Http\Requests\Account;

use App\Rules\PasswordCheckRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
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
