<?php

namespace App\Rules;

use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;

class UniqueProductName implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $company_id;
    protected $store_id;
    protected $category_id;

    public function __construct($company_id, $store_id, $category_id)
    {
        $this->company_id = $company_id;
        $this->store_id = $store_id;
        $this->category_id = $category_id;


        // $this->company_id = $company_id;
        // $storeExists = Store::where('id', $store_id)->exists();
        // if($storeExists)
        // {
        //     $store = Store::where('id', $store_id)->first();
        //     $this->name_of_store = $store->name_of_store;
        // }
        // else
        // {
        //     $this->name_of_store=null;
        // }
        // $categoryExist = Category::where('id', $category_id)->exists();
        // if($categoryExist)
        // {
        //     $category = Category::where('id', $category_id)->first();
        //     $this->category_name = $category->category;
        // }
        // else
        // {
        //     $this->category_name=null;
        // }
        // // dd($company_id,$this->name_of_store, $this->category_name );

    }

    public function passes($attribute, $value)
    {
        // Check if the product name is unique for the given company_id and store_id
        return !Product::where('product_name', $value)
            ->where('company_id', $this->company_id)
            ->where('store_id', $this->store_id)
            ->where('category_id', $this->category_id)
            ->exists();
    }

    public function message()
    {
        return 'The product name is already taken for this company and store.';
    }
}
