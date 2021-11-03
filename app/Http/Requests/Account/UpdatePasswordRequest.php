<?php

namespace App\Http\Requests\Account;

use App\Rules\PasswordCheckRule;
use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
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
