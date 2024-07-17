<?php

namespace App\Imports;
use Validator;
use App\Models\Store;
use App\Models\StoreLocation;
use App\Rules\UniqueStoreName;
use App\Rules\UniqueLocationInStore;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ImportStore implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // dd($row);
      $store = Store::select('name_of_store')->where('name_of_store', $row['name_of_store'])->first();
      $store_location_id = StoreLocation::select('id')->where('location', $row['location'])->first();
      
      $validator = Validator::make($row, [
        'company_id' => 'required',
        'name_of_store' => 'required',
        'location' => ['required',new UniqueLocationInStore($row['company_id'],$store->name_of_store??null)],
        'parish' => 'required',
        'channel' => 'required',
        ]);
    
        if (!$validator->fails()) 
        {
            // Validation failed

            $storeExists = Store::where('name_of_store', $row['name_of_store'])->exists();

            // dd($storeExists);
    
            if($storeExists)
            {
                $store = Store::where('name_of_store', $row['name_of_store'])->first();
                $store_id=$store->id;
                $existingParish= json_decode($store->parish, true);
                $parish= [$row['parish']];
                // dd($existingParish, $parish );

                $existingParish = array_merge($existingParish,  $parish);
                // dd($existingParish, $parish );

                // dd($existingParish);
                $store->parish= json_encode($existingParish);
                $store->save();
    
                $store->locations()->create(['location' => $row['location']]);
                // dd($store);
                return $store;
            }
            else
            {
                $company_id = $row['company_id'];
        
                $parish=[$row['parish']];
                // dd($parish);
                    $store = new Store([
                        'company_id' => $company_id,
                        'name_of_store' => $row['name_of_store'],
                        'parish' => json_encode($parish),
                        'channel' => $row['channel'],
                    ]);

                    // dd($store);
                    $store->save();
        
                    $store->locations()->create(['location' => $row['location']]);

                // dd($store,'fakhaee');
                return $store;
        
            }
        }
        else
        {
            return;
        }
    }
}