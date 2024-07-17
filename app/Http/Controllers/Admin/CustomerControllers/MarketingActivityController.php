<?php

namespace App\Http\Controllers\Admin\CustomerControllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
use App\Models\MarketingActivity;
use App\Models\StockCountByStores;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MarketingActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageSidebar' => 'marketing-activity'];

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
        $marketingActivityIDArr = [];
        foreach ($stores as $store) {
            $marketingActivitiesData = $store->marketingActivities->pluck('id')->toArray(); // Pluck product IDs
            $marketingActivityIDArr = array_merge($marketingActivityIDArr, $marketingActivitiesData); // Merge product IDs
        }
        // dd($marketingActivityIDArr);
        $marketingActivityData = MarketingActivity::whereIn('id', $marketingActivityIDArr)->get();

        $currentUser = Auth::user();
        $userTimeZone  = $currentUser->time_zone;

        foreach ($marketingActivityData as $key => $marketingActivity) {
            $marketingActivity->created_at = convertToTimeZone($marketingActivity->created_at, 'UTC', $userTimeZone);
            $marketingActivity->date_modified = convertToTimeZone($marketingActivity->date_modified, 'UTC', $userTimeZone);
        }

        // dd($marketingActivityData);

        $userId=$user->id;
        $name=$user->name;

        return view('manager.marketingActivity', compact('marketingActivityData','userArr', 'name',  'stores','allLocations','categories', 'products'), ['pageConfigs' => $pageConfigs]);
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
