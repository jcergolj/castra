<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class UpdateImageRequest extends FormRequest
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
            'profile_image' => [
                'required',
                'image',
                'dimensions:min_width=100,min_height=100',
            ],
        ];
    }
}
