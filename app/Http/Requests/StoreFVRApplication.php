<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFVRApplication extends FormRequest
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
            'boat_name' => ['required'],
            'boat_color' => ['required'],
//            'engine_motor_no' => ['unique:bancas,engine_motor_no,' . request('banca_id'), 'nullable']
        ];
    }
}
