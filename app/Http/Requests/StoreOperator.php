<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOperator extends FormRequest
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
            'last_name' => ['required'],
            'first_name' => ['nullable'],
            'middle_name' => ['nullable'],
            'birth_date' => ['nullable'],
            'sex' => ['required'],
            'civ_stat' => ['required'],
            'mobile' => ['nullable'],
            'email' => ['nullable'],
            'brgy_desc' => ['nullable'],
            'address' => ['required'],
        ];
    }
}
