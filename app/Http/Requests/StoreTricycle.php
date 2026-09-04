<?php

namespace App\Http\Requests;

use App\Models\Tricycle;
use App\Rules\CheckIfBodyNumberIsExceeded;
use Illuminate\Foundation\Http\FormRequest;

class StoreTricycle extends FormRequest
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
     * The tricycle master stores serial numbers uppercase and trimmed. Normalise
     * them BEFORE validation runs, otherwise the unique rules compare the raw
     * input against already-uppercased rows and a duplicate slips through in a
     * different casing, or an existing record is reported as free when it is not.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $normalised = [];

        foreach (['body_number', 'make_type', 'engine_motor_no', 'chassis_no', 'plate_no'] as $field) {
            if ($this->has($field) && is_string($this->input($field))) {
                $normalised[$field] = strtoupper(trim($this->input($field)));
            }
        }

        $this->merge($normalised);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Tricycle::VALIDATION_RULE;

        if($this->getMethod() !== 'POST') {
            $rules += ['body_number' => ['required','unique:tricycles,body_number,' . request('id'), new CheckIfBodyNumberIsExceeded()]];
            $rules['engine_motor_no'] = ['unique:tricycles,engine_motor_no,' . request('id'), 'nullable'];
            $rules['chassis_no'] = ['unique:tricycles,chassis_no,' . request('id'), 'nullable'];
            $rules['plate_no'] = ['unique:tricycles,plate_no,' . request('id'), 'nullable'];
        } else {
            $rules += ['body_number' => ['required','unique:tricycles,body_number', new CheckIfBodyNumberIsExceeded()]];
        }

//        dd($rules);

        return $rules;
    }
}
