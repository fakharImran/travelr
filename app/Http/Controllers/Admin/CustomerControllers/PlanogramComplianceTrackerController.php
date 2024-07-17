<?php

namespace App\Http\Controllers\Admin\CustomerControllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
use App\Models\StockCountByStores;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PlanogramComplianceTracker;

class PlanogramComplianceTrackerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageSidebar' => 'planogram-compliance-tracker'];

        $user= Auth::user();
        $merchandiserUsers = User::role('merchandiser')->get();

        //   dd($merchandiserUsers);
        $merchandiserArray = array();
        $allLocations=StoreLocation::all();
        $compnay_users = $user->companyUser->company->companyUsers;
        $userArr = array();
        foreach ($compnay_users as $key => $compnay_user) {
            if($compnay_user->user->hasRole('merchandiser')){
                array_push($userArr, $compnay_user->user)  ;
            }
        }
        $stores= $user->companyUser->company->stores;
        $products = [];
        foreach ($stores as $store) {
//            $storeProducts = $store->products->pluck('id')->toArray(); // Pluck product IDs
//            $products = array_merge($products, $storeProducts); // Merge product IDs
        }
        $products = Product::whereIn('id', $products)->get();

        $categories = [];

        foreach ($products as $product) {
            $productCategories = $product->category->pluck('id')->toArray(); // Pluck product IDs
            $categories = array_merge($categories, $productCategories); // Merge product IDs
        }
        $categories = Category::whereIn('id', $categories)->get();
        // dd($categories);

        $planogramArr = [];
        foreach ($stores as $store) {
            $planogramComplianceData = $store->planogramComplianceTrackers->pluck('id')->toArray(); // Pluck product IDs
            $planogramArr = array_merge($planogramArr, $planogramComplianceData); // Merge product IDs
        }
        // dd($planogramArr);
        $planogramComplianceData = PlanogramComplianceTracker::whereIn('id', $planogramArr)->get();

        $currentUser = Auth::user();
        $userTimeZone  = $currentUser->time_zone;

        foreach ($planogramComplianceData as $key => $planogram) {
            $planogram->created_at = convertToTimeZone($planogram->created_at, 'UTC', $userTimeZone);
            $planogram->date_modified = convertToTimeZone($planogram->date_modified, 'UTC', $userTimeZone);
        }

        // dd($planogramComplianceData);

        $userId=$user->id;
        $name=$user->name;

        return view('manager.planogramComplianceTracker', compact('planogramComplianceData','userArr', 'name',  'stores','allLocations','categories', 'products'), ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        //
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
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
