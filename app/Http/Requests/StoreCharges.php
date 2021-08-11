<?php

namespace App\Http\Requests;

use App\Models\Charge;
use App\Rules\CheckDroppingIfExisting;
use App\Rules\CheckDroppingIfExistingUpdate;
use Illuminate\Foundation\Http\FormRequest;

class StoreCharges extends FormRequest
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
        $rules = Charge::VALIDATION_RULE;
        if(request('IsDroppingChecked')) {
            $rules += $this->checkIfDroppingIsSet(request('drop'), $rules);
        }

        if($this->getMethod() != 'POST') {
            $rules['name'] = 'unique:charges,name,' . request('id');
        }

        return $rules;
    }

    public function checkIfDroppingIsSet($drop, $rules) {
        return $this->getMethod() == 'POST' ? ['IsDroppingChecked' => [new CheckDroppingIfExisting()]] : ['IsDroppingChecked' => [new CheckDroppingIfExistingUpdate()]];
    }
}
