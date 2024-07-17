<?php

namespace App\Rules;

use App\Models\Company;
use Illuminate\Contracts\Validation\Rule;

class UniqueCompanyName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function passes($attribute, $value)
    {
        // Check if the store name is unique
        return !Company::where('company', $value)->exists();
    }

    public function message()
    {
        return 'The Company name is already taken.';
    }
}
