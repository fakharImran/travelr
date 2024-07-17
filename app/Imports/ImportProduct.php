<?php

namespace App\Imports;

use Validator;
use App\Models\Store;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use App\Rules\UniqueProductName;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportProduct implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // dd($row['company_id']);
        $company= Company::where('id', $row['company_id'])->first();
        if (!($row['store_name'] == 'all' || $row['store_name'] == 'All')) {
            $store_id = Store::select('*')->where('name_of_store', $row['store_name'])
            ->where('company_id',$row['company_id'])->first();
            // dd($store_id);
            $category_id = Category::select('*')->where('category', $row['category_name']) ->where('company_id',$row['company_id'])->first();
            $validator = Validator::make($row, [
                'company_id' => 'required',
                'store_name' => 'required',
                'category_name' => 'required',
                'product_name' => ['required',new UniqueProductName($row['company_id'], $store_id->id??null ,$category_id->id??null)],
                'product_number_sku' => 'required',
                'competitor_product_name' => 'required',
            ]);
            // dd($validator);
            if (!$validator->fails()) {
                // Validation failed

                try {
                    $competitorArr= explode(',', $row['competitor_product_name']);
                    // Process the data
                    $product = new Product([
                        'company_id' => $row['company_id'],
                        'store_id' => $store_id->id,
                        'category_id' => $category_id->id,
                        'product_name' => $row['product_name'],
                        'product_number_sku' => $row['product_number_sku'],
                        'competitor_product_name' => json_encode($competitorArr),
                    ]);
                    $product->save();
                    return $product;
                } catch (\Throwable $e) {
                    // Handle the exception, log or display an error message
                    // For example, you can log the error using `error_log` or use Laravel's logger: \Illuminate\Support\Facades\Log::error($e);
                    // Return null or throw a custom exception, depending on your needs
                    return ;
                }
            }
            else{
                return;
            }

        }
        else{
            $validator = Validator::make($row, [
                'company_id' => 'required',
                'store_name' => 'required',
                'category_name' => 'required',
                'product_name' => 'required',
                'product_number_sku' => 'required',
                'competitor_product_name' => 'required',
            ]);
            if (!$validator->fails()) {
                // $stores = Store::all();
//                $stores = Store::select('*')->where('company_id', $row['company_id'])->get();
                // dd($stores);
            $category_id = Category::select('*')->where('category', $row['category_name']) ->where('company_id',$row['company_id'])->first();
//                foreach ($stores as $key => $store) {
                    $validator = Validator::make($row, [
                        'product_name' => ['required',new UniqueProductName($row['company_id'], null ,$category_id->id??null)],
                    ]);
                    if (!$validator->fails()) {
                        // Validation failed
                        try {
                            $competitorArr= explode(',', $row['competitor_product_name']);
                            // Process the data
                            $product = new Product([
                                'company_id' => $row['company_id'],
                                'store_id' => null,
                                'category_id' => $category_id->id,
                                'product_name' => $row['product_name'],
                                'product_number_sku' => $row['product_number_sku'],
                                'competitor_product_name' => json_encode($competitorArr),
                            ]);
                            $product->save();
                        } catch (\Throwable $e) {
                        }
                    }
                    else{
                    }

//                }
            }
            else{
                return;
            }
        }
    }
}
