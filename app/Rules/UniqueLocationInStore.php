<?php

namespace App\Rules;

use App\Models\Store;
use App\Models\StoreLocation;
use Illuminate\Contracts\Validation\Rule;

class UniqueLocationInStore implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $company_id;
    protected $store_id;

    public function __construct($company_id,$name_of_store)
    {
        $this->company_id = $company_id;
        $storeExists = Store::where('name_of_store', $name_of_store)->exists();

        if($storeExists)
        {
            $store = Store::where('name_of_store', $name_of_store)->first();
            $this->store_id = $store->id;
        }
        else
        {
            $this->store=null;
        }
        
        // dd($this->store_id);
    }

    public function passes($attribute, $location)
    {
        // dd($location);
        // Check if the store name is unique for the given company_id
        // $storelocation = StoreLocation::where('location', $location)
        //     ->where('store_id', $this->store_id)
        //     ->exists();

        //  $store = Store::where('company_id', $this->company_id)
        //     ->where('id', $this->store_id)
        //     ->exists();

        $storelocation = StoreLocation::where('location', $location)
        ->where('store_id', $this->store_id)
        ->exists();

    // Check if the location is the same as the current store's location
    $store = Store::where('id', $this->store_id)
        ->where('company_id', $this->company_id)
        ->whereDoesntHave('locations', function ($query) use ($location) {
            $query->where('location', $location);
        })
         ->exists();

        return !($storelocation==true && $store == true)?true:false;
    }

    public function message()
    {
        return 'The location name is already taken for this store.';
    }
    
}
