<?php

namespace App\Http\Requests;

use App\Models\Banca;
use Illuminate\Foundation\Http\FormRequest;

class StoreBanca extends FormRequest
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
        $rules = Banca::VALIDATION_RULE;

        if($this->getMethod() == 'POST') {
            $rules += ['body_number' => ['unique:bancas,body_number']];
            $rules += ['engine_motor_no' => ['unique:bancas,engine_motor_no', 'nullable']];
        }
        else {
            $rules += ['body_number' => ['unique:bancas,body_number,' . request('id'), 'required']];
            $rules += ['engine_motor_no' => ['unique:bancas,engine_motor_no,' . request('id'), 'nullable']];
        }

        return $rules;




    }
}
