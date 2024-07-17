<?php

namespace App\Http\Controllers\Admin\CustomerControllers;

use DateTime;
use DateTimeZone;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\OutOfStock;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
use App\Models\StockCountByStores;
use App\Http\Controllers\Controller;
use App\Models\ProductExpiryTracker;
use Illuminate\Support\Facades\Auth;
use App\Models\MerchandiserTimeSheet;
use Illuminate\Support\Facades\Config;

class BusinessOverviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index()
    {

        $pageConfigs = ['pageSidebar' => 'business-overview'];

        $user= Auth::user();

        $compnay_users = $user->companyUser->company->companyUsers;

        $userArr = array(); //this user array is merchandisers in this company
        foreach ($compnay_users as $key => $compnay_user) {
            if($compnay_user->user->hasRole('merchandiser')){
                array_push($userArr, $compnay_user->user)  ;
            }
        }
        $stores= $user->companyUser->company->stores; //these are stores of this company

        $products = [];
        foreach ($stores as $store) {
            //$storeProducts = $store->products->pluck('id')->toArray(); // Pluck product IDs
            //$products = array_merge($products, $storeProducts); // Merge product IDs
        }
        $products = Product::whereIn('id', $products)->get();  //these are products of this company

        $categories = [];

        foreach ($products as $product) {
            $productCategories = $product->category->pluck('id')->toArray(); // Pluck product IDs
            $categories = array_merge($categories, $productCategories); // Merge product IDs
        }
        $categories = Category::whereIn('id', $categories)->get(); //these are categories of this company
        // dd($categories);
        $stockCountByStoreArr = [];
        foreach ($stores as $store) {
            $storestockCountByStoreData = $store->stockCountByStores->pluck('id')->toArray(); // Pluck product IDs
            $stockCountByStoreArr = array_merge($stockCountByStoreArr, $storestockCountByStoreData); // Merge product IDs
        }
        // dd($stockCountByStoreArr);
        $stockCountData = StockCountByStores::whereIn('id', $stockCountByStoreArr)->get();  //these are stock count by store data of this company

        $currentUser = Auth::user();
        $userTimeZone  = $currentUser->time_zone;

        foreach ($stockCountData as $key => $stockCount) {
            $stockCount->created_at = convertToTimeZone($stockCount->created_at, 'UTC', $userTimeZone);
            $stockCount->date_modified = convertToTimeZone($stockCount->date_modified, 'UTC', $userTimeZone);
        }


        $outOfStockIDArr = [];
        foreach ($stores as $store) {
            $outOfStocksData = $store->outOfStocks->pluck('id')->toArray(); // Pluck product IDs
            $outOfStockIDArr = array_merge($outOfStockIDArr, $outOfStocksData); // Merge product IDs
        }
        // dd($outOfStockIDArr);
        $outOfStockData = OutOfStock::whereIn('id', $outOfStockIDArr)->get();   //these are Out of stock data of this company

        foreach ($outOfStockData as $key => $outOfStock) {
            $outOfStock->created_at = convertToTimeZone($outOfStock->created_at, 'UTC', $userTimeZone);
            $outOfStock->date_modified = convertToTimeZone($outOfStock->date_modified, 'UTC', $userTimeZone);
        }

        $productExpiryTrackerIDArr = [];
        foreach ($stores as $store) {
            $productExpiryTrackersData = $store->productExpiryTrackers->pluck('id')->toArray(); // Pluck product IDs
            $productExpiryTrackerIDArr = array_merge($productExpiryTrackerIDArr, $productExpiryTrackersData); // Merge product IDs
        }
        // dd($productExpiryTrackerIDArr);
        $productExpiryTrackerData = ProductExpiryTracker::whereIn('id', $productExpiryTrackerIDArr)->get();  //these are Product expiry dracker data of this company

        foreach ($productExpiryTrackerData as $key => $productExpiry) {
            $productExpiry->created_at = convertToTimeZone($productExpiry->created_at, 'UTC', $userTimeZone);
            $productExpiry->date_modified = convertToTimeZone($productExpiry->date_modified, 'UTC', $userTimeZone);
        }

        $userId=$user->id;
        $name=$user->name;
        $currentUser=$user->companyUser->company->companyUsers;

        $uniqueServicedStoreLocation = array(); //all unique serviced store's location
        $todayUniqueServicedStoreLocation = array(); // today's unique serviced store's location
        foreach ($currentUser as $key => $userData) {
            // dd($user->companyUser->company->companyUsers);
            // $merchandiserTimeSheetData=MerchandiserTimeSheet::all();
            $uniqueServicedStore = $userData->timeSheets;
            // dd($userData->timeSheets);
            foreach ($uniqueServicedStore as $key => $merchandiser) {
                $merchandiser->created_at = convertToTimeZone($merchandiser->created_at, 'UTC', $userTimeZone);
                $merchandiser->date_modified = convertToTimeZone($merchandiser->date_modified, 'UTC', $userTimeZone);
                // dd(date('Y-m-d'), date("Y-m-d", strtotime($merchandiser->created_at))    );
                if(date("Y-m-d", strtotime($merchandiser->created_at)) == date('Y-m-d')){
                    array_push($todayUniqueServicedStoreLocation, $merchandiser);
                }

                array_push($uniqueServicedStoreLocation, $merchandiser);

            }
        }
       $uniqueNumberOfStoreServicedCount=0;
        $arr = array();
        $channel_arr = array();
        $servicedChannel_arr = array();
        $uniqueDates = [];
        $uniqueLocation = [];

        foreach ($stores as $store) {
            $store_parish = json_decode($store->parish, true);
            //for map
            foreach ($store_parish as $key => $parish) {
                $store_parish[$key] = strtolower(str_replace([' ', '.'], '', $parish)) . "_" . strtolower(str_replace(' ', '', $store->channel));
                // $channel_arr = array_merge($channel_arr, [strtolower(str_replace(' ', '', $store->channel))]);
            }
            $arr = array_merge($arr, $store_parish);

            //for card after slash value
            foreach ($store->locations as $location) {
                // dd($location->location);
                $channel_arr = array_merge($channel_arr, [strtolower(str_replace(' ', '', $store->channel))]);
            }

            //for card before slash value
            $merchandiserTimeSheets = $store->merchandiserTimeSheets;
                // Initialize an array to store unique dates

            foreach ($merchandiserTimeSheets as $key => $merchandiserTimeSheet) {
                // dd($merchandiserTimeSheet);
                // Convert the created_at timestamp to the desired timezone (UTC)
                $createdAt = $merchandiserTimeSheet->created_at;
                $createdAt->setTimezone(new DateTimeZone($userTimeZone));
                // dd($createdAt,$merchandiserTimeSheet->created_at );

                // Format the created_at timestamp to compare with today's date
                $createdAtFormatted = $createdAt->format('Y-m-d');

                // Get today's date in UTC
                $todayInUTC = (new DateTime())->setTimezone(new DateTimeZone($userTimeZone))->format('Y-m-d');
                // dd($todayInUTC, $createdAtFormatted);
                // Compare the formatted dates to check if the created_at is today
                if ($createdAtFormatted == $todayInUTC) {
                    // Check if the date is not already processed
                    if ((!in_array($createdAtFormatted, $uniqueDates)) ||  (!in_array($merchandiserTimeSheet->store_location_id, $uniqueLocation))) {
                        // The created_at timestamp is from today and has not been processed yet
                        // Your code here

                        $servicedChannel_arr = array_merge($servicedChannel_arr, [strtolower(str_replace(' ', '', $store->channel))]);
                        $uniqueNumberOfStoreServicedCount++;
                        // Add the date to the list of processed dates
                        array_push($uniqueDates,$createdAtFormatted);
                        array_push($uniqueLocation,$merchandiserTimeSheet->store_location_id);
                    }
                }
            }


        }
        // dd($channel_arr, $arr);

        // these below values for cards
        $totalNumberServicedChannelbyLocation = array_count_values($servicedChannel_arr);
        $locationChannelTotalCount = array_count_values($channel_arr);
            // dd($uniqueDates,$uniqueLocation, $todayInUTC, $servicedChannel_arr);

        // Count the occurrences of each element
        // display parish channel values for map
        $parishChannelCount = array_count_values($arr);
        // dd($uniqueNumberOfStoreServicedCount);
        // dd($parishChannelTotalCount, $channel_arr, $parishChannelCount, $totalNumberOfParish);

        return view('manager.businessOverview', compact('productExpiryTrackerData','outOfStockData','stockCountData','userArr', 'name',  'stores', 'products','categories', 'uniqueServicedStoreLocation', 'parishChannelCount', 'locationChannelTotalCount', 'todayUniqueServicedStoreLocation', 'totalNumberServicedChannelbyLocation','uniqueNumberOfStoreServicedCount'), ['pageConfigs' => $pageConfigs]);
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
