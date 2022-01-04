<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeletedUserRequest extends FormRequest
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
            'ids' => ['required', 'array'],
            'ids.*' => [
                'exists:users,id', function ($attribute, $value, $fail) {
                    if ($value === user()->id) {
                        $fail('You cannot delete yourself.');
                    }
                },
            ],
        ];
    }
}
