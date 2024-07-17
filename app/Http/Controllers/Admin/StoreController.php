<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Validator;
use App\Models\Store;
use App\Models\Company;

use App\Exports\ExportStore;

use App\Imports\ImportStore;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
use App\Rules\UniqueStoreName;
use App\Http\Controllers\Controller;
use App\Rules\UniqueLocationInStore;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Imports\StoreImport; // Replace with your actual import class



class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageSidebar' => 'store'];    
        $stores= Store::select('*')->get();        
        // dd($stores[15]->locations);
        
        $currentUser = Auth::user();
        $userTimeZone  = $currentUser->time_zone;
        foreach ($stores as $key => $store) {
            $store->created_at = convertToTimeZone($store->created_at, 'UTC', $userTimeZone);
            $store->updated_at = convertToTimeZone($store->updated_at, 'UTC', $userTimeZone);
        }

        return view('admin.store.index', compact('stores'), ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageSidebar' => 'store'];    

        $companies= Company::select('*')->get();
        return view('admin.store.create', compact('companies'), ['pageConfigs' => $pageConfigs]);

        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'name_of_store' => 'required',
            'location' => ['required',new UniqueLocationInStore($request->company_id,$request->name_of_store)],
            'parish' => 'required',
            'channel' => 'required',
        ]);
        
    
        if ($validator->fails()) {
            // Validation failed
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $storeExists = Store::where('name_of_store', $request->name_of_store)->exists();

        // dd($storeExists);

        if($storeExists)
        {
            $store = Store::where('name_of_store', $request->name_of_store)->first();
            $store_id=$store->id;
            $existingParish= json_decode($store->parish, true);
            array_push($existingParish, $request->parish);

            // dd($uniqueCheck);
            $store->parish= json_encode($existingParish);
            $store->save();

            $store->locations()->create(['location' => $request->location]);
            // dd($store);
        }
        else
        {
            $tempUser = new Store();
            $tempUser->company_id = $request->company_id ?? null;
            $tempUser->name_of_store = $request->name_of_store ?? null;
            $tempUser->parish = json_encode([$request->parish]);
            $tempUser->channel = $request->channel ?? null;
            $tempUser->save();
            $store_id= $tempUser->id;
            // Store the locations
    
            if ($request->has('location')) 
            {
                $tempUser->locations()->create(['location' => $request->location]);
            }
    
        }
        
        return redirect()->route('store.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($target, $id)
    {
        $pageConfigs = ['pageSidebar' => 'store'];    

        $companies= Company::select('*')->get();
        $store= Store::select()->where('id',$id)->first();
        return view('admin.store.edit', compact('store', 'id','companies'), ['pageConfigs' => $pageConfigs]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{
    // Validate the input data
    // dd($request->all());
    $validator = Validator::make($request->all(), [
        'company_id' => 'required',
        'name_of_store' => 'required',
        'locations' => ['required',new UniqueLocationInStore($request->company_id,$request->name_of_store)],
        'parish' => 'required',
        'channel' => 'required',
    ]);

    if ($validator->fails()) {
        // Validation failed
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Find the store by its ID
    $store = Store::find($id);

    if (!$store) {
        // Handle the case where the store with the given ID is not found
        return redirect()->route('store.index')->with('error', 'Store not found.');
    }

    // Update the store attributes with the new data
    $store->company_id = $request->company_id ?? null;
    $store->name_of_store = $request->name_of_store ?? null;
    $store->parish = json_encode($request->parish);
    $store->channel = $request->channel ?? null;

    // Save the store record with the updated data
    $store->save();

    // Update store locations
    if ($request->has('locations')) {
        // dd($request->locations);
        $store->locations()->delete(); // Remove existing locations
        foreach ($request->locations as $location) {
            $store->locations()->create(['location' => $location]);
        }
    }

    return redirect()->route('store.index')->with('success', 'Store updated successfully');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            // Find the item with the given ID and delete it
            $item = Store::find($id);
            if ($item) {
                $item->delete();
                return redirect()->route('store.index');
            } else {
                return redirect()->back()->withErrors(['error' => 'Item not found']);
                // return response()->json(['error' => 'Item not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong while deleting the item']);
        }
    }


    // public function importFile(Request $request)
    // {
    //     $request->validate([
    //         'import_file' => 'required|mimes:csv,xls,xlsx'
    //     ]);

    //     $file = $request->file('import_file');

    //     try {
    //         Excel::import(new StoreImport, $file);
    //         return redirect()->back()->with('success', 'File imported successfully.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Error importing file. Please check the format and try again.');
    //     }
    // }

    public function importView(Request $request){
        return view('importFile');
    }

    public function import(Request $request){
        try {
            // Validate the uploaded file before processing
            $request->validate([
                'file' => 'required|mimes:xlsx,xls|max:100000',
            ]);

            // Store the uploaded file
            $filePath = $request->file('file')->store('files');
            // dd($filePath);
            // Import the data from the Excel file using the ImportStore class
            Excel::import(new ImportStore, $filePath);

            // Redirect back with success message
            return redirect()->back()->with('success', 'File imported successfully.');
        } 
        // catch (ValidationException $e) {
        //     // Handle validation exceptions (e.g., invalid data in the Excel file)
        //     return redirect()->back()->withErrors($e->errors())->withInput();
        // } 
        catch (Exception $e) {
            // Handle other exceptions that occur during the import process
            return redirect()->back()->with('error', 'Error occurred during file import please upload again with valid format.  ' );
            // $e->getMessage()
        }
    }

    public function exportUsers(Request $request){
        // dd($request->all());
        return Excel::download(new ExportStore, 'stores.xlsx');
    }
}
