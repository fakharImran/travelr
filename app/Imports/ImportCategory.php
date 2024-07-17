<?php

namespace App\Imports;

use Validator;
use App\Models\Category;
use App\Rules\UniqueCategoryName;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportCategory implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // dd($row);
        $validator = Validator::make($row, [
            'company_id' =>'required',
            'category' => ['required', new UniqueCategoryName($row['company_id'])],
        ]);

        if (!$validator->fails()) 
        {
            try {
                // Process the data
                $category = new Category([
                    'company_id' => $row['company_id'],
                    'category' => $row['category'],
                ]);

                return $category;
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
}
