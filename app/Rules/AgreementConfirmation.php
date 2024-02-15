<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AgreementConfirmation implements Rule
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
        // mysqlでbooleanが0,1で比較されるため===ではなく==で比較している
        return $value == true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be true';
    }
}
