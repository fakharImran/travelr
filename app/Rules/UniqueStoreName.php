<?php

namespace App\Rules;

use App\Models\Store;
use Illuminate\Contracts\Validation\Rule;

class UniqueStoreName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $company_id;

    public function __construct($company_id)
    {
        $this->company_id = $company_id;
    }

    public function passes($attribute, $value)
    {
        // Check if the store name is unique for the given company_id
        return !Store::where('name_of_store', $value)
            ->where('company_id', $this->company_id)
            ->exists();
    }

    public function message()
    {
        return 'The store name is already taken for this company.';
    }
    
}
