<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Models\User;
use App\Models\Store;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use App\Models\CompanyUser;
use Illuminate\Http\Request;
use App\Rules\UniqueCompanyName;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $pageConfigs = ['pageSidebar' => 'company'];    
        $companies= Company::select('*')->get();
       
        $currentUser = Auth::user();
        $userTimeZone  = $currentUser->time_zone;

        foreach ($companies as $key => $company) {
            $company->created_at = convertToTimeZone($company->created_at, 'UTC', $userTimeZone);
            $company->updated_at = convertToTimeZone($company->updated_at, 'UTC', $userTimeZone);
        }

        
        return view('admin.company.index', compact('companies'), ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageSidebar' => 'company'];    
        return view('admin.company.create', ['pageConfigs' => $pageConfigs]);
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
        $validator = Validator::make($request->all(), [
            'company_name' => ['required', new UniqueCompanyName],
            'company_code' => 'required|regex:/\d{4}/'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tempCompany= new Company();
        $tempCompany->company= $request->company_name;
        $tempCompany->code= $request->company_code;
        $tempCompany->save();
        $companies= Company::select('*')->get();
        $companies=json_decode($companies,true);
        return redirect()->route('company.index');
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
        $pageConfigs = ['pageSidebar' => 'company'];    

        $companyData= Company::select()->where('id',$id)->get();
        $compData= json_decode($companyData,true);
        $company=$compData[0];
        return view('admin.company.edit', compact('company', 'id'), ['pageConfigs' => $pageConfigs]);
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
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'company_code' => 'required|regex:/\d{4}/'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $query =  Company::where('id', $id)->update(['company'=>$request->company_name, 'code' =>$request->company_code]);
        return redirect()->route('company.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        
    // Find the company by its ID
    $company = Company::find($id);

    // Check if the company exists
    if (!$company) {
        return response()->json(['message' => 'Company not found'], 404);
    }

    // Delete the company and its related records (categories and products) due to cascade delete
    $company->delete();

    return redirect()->route('company.index');

    return response()->json(['message' => 'Company and associated data deleted successfully']);

        // try {
        //     // Find the item with the given ID and delete it
        //     $item = Company::find($id);
        //     if ($item) {
        //         if($item->delete()){
        //             foreach ($item->companyUsers as $companyUser) {
        //                 $user = User::find($companyUser['user_id']);
        //                 $user->delete();
        //                 $companyUser = CompanyUser::find($companyUser['id']);
        //                 $companyUser->delete();
        //             }

        //             foreach ($item->stores as $store) {
        //                 $stor = Store::find($store['id']);
        //                 $stor->delete();
        //             }
        //             foreach ($item->categories as $category) {
        //                 $category = Category::find($category['id']);
        //                 $category->delete();
        //             }
        //             session()->flash("success", "Company deleted successfully");
        //         }
        //         return redirect()->route('company.index');
        //     } else {
        //         return redirect()->back()->withErrors(['error' => 'Item not found']);
        //         // return response()->json(['error' => 'Item not found']);
        //     }
        // } catch (\Exception $e) {
        //     return response()->json(['error' => 'Something went wrong while deleting the item']);
        // }
    }


}
