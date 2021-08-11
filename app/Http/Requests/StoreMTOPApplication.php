<?php

namespace App\Http\Requests;

use App\Models\MtopApplication;
use Illuminate\Foundation\Http\FormRequest;

class StoreMTOPApplication extends FormRequest
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
        $rules = MtopApplication::VALIDATION_RULE;

        if(request('change_unit') === true) {
            $rules += ['change_unit_details.new_chassis_no' => ['required', 'unique:tricycles,chassis_no']];
            $rules += ['change_unit_details.new_engine_motor_no' => ['required', 'unique:tricycles,engine_motor_no']];
            $rules += ['change_unit_details.new_plate_no' => ['unique:tricycles,plate_no', 'nullable']];
            $rules += ['change_unit_details.new_make_type' => ['required']];
        }

        if(request('dropping') === true) {
            $rules += ['dropping_details.new_operator_id' => ['required']];
        }

        return $rules;
    }
}
