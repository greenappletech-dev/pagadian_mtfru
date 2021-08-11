<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CheckIfBodyNumberIsExceeded implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

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
        $m99_table = DB::table('m99')->where('par_code', '001')->first();

        $last_body_number = $m99_table->body_number_to;
        $body_number_to_use = request('body_number');

        if($last_body_number >= (int)$body_number_to_use) {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Body Number Already Exceeded the Current Settings.';
    }
}
