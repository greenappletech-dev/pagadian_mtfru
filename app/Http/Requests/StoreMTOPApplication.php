<?php

namespace App\Http\Requests;

use App\Models\MtopApplication;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

            /* the tricycle this application is changing. once the change unit has
               been written to the master, re-opening the same application used to
               collide with its own row and could never be saved again, so the
               record being edited is excluded from the uniqueness check. */
            $tricycle_id = request('tricycle_id');

            $rules += ['change_unit_details.new_chassis_no' => ['required', Rule::unique('tricycles', 'chassis_no')->ignore($tricycle_id)]];
            $rules += ['change_unit_details.new_engine_motor_no' => ['required', Rule::unique('tricycles', 'engine_motor_no')->ignore($tricycle_id)]];
            $rules += ['change_unit_details.new_plate_no' => [Rule::unique('tricycles', 'plate_no')->ignore($tricycle_id), 'nullable']];
            $rules += ['change_unit_details.new_make_type' => ['required']];
        }

        if(request('dropping') === true) {
            $rules += ['dropping_details.new_operator_id' => ['required']];
        }

        return $rules;
    }
}
