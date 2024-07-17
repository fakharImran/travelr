<?php

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;

class UniqueCategoryName implements Rule
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
        return !Category::where('category', $value)
            ->where('company_id', $this->company_id)
            ->exists();
    }

    public function message()
    {
        return 'The Category name is already taken for this company.';
    }
    
}
