<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
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
            //
        ];
    }
}
