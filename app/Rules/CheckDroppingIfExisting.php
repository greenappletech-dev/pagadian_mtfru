<?php

namespace App\Rules;

use App\Models\Charge;
use Illuminate\Contracts\Validation\Rule;

class CheckDroppingIfExisting implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Charge::where('drop_default', '!=', null)->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Dropping Fee is Already Set.';
    }
}
