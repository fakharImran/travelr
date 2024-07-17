<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelExportController extends Controller
{
    
    public function export(Request $request)
    {
        // Your logic to fetch table data goes here
        $tableData = [
            ['Column 1', 'Column 2', 'Column 3'],
            ['Data 1', 'Data 2', 'Data 3'],
            ['Data 4', 'Data 5', 'Data 6'],
            // Add more rows as needed
        ];

        // Create a new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Populate the spreadsheet with table data
        $row = 1;
        foreach ($tableData as $data) {
            $column = 1;
            foreach ($data as $value) {
                $sheet->setCellValueByColumnAndRow($column, $row, $value);
                $column++;
            }
            $row++;
        }
        // dd($tableData);

        // Set the response headers to serve as an Excel file
        $response = response()->stream(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200);

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="table_data.xlsx"');

        return $response;
    }
    
    //
}
