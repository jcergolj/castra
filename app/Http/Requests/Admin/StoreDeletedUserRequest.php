<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeletedUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
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
