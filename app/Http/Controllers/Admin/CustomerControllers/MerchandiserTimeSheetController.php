<?php

namespace App\Http\Controllers\Admin\CustomerControllers;

use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MerchandiserTimeSheet;

class MerchandiserTimeSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkTimeSheetStatus($timesheets)
    {
        // dd($timesheets);

        foreach ($timesheets as $key => $time_sheet) {
            // dd($lastTimeSheetRecord = $time_sheet->timeSheetRecords()->latest()->first()->status );
                    // dd($time_sheet);
                    $firstTimeSheetRecord = $time_sheet->timeSheetRecords()->first();
                    $lastTimeSheetRecord = $time_sheet->timeSheetRecords()->latest()->first();

                if($lastTimeSheetRecord->status!='check-out')
                {
                    // dd($firstTimeSheetRecord,$lastTimeSheetRecord);

                    $checkin_date_time = $firstTimeSheetRecord->date . ' ' . $firstTimeSheetRecord->time;

                    $checkinDateTime = new DateTime($checkin_date_time);
                    $currentDateTime = new DateTime();

                    $interval = $checkinDateTime->diff($currentDateTime);

                    // Format the date and time
                    $formattedDateTime = $currentDateTime->format('Y-m-d H:i A');

                    $timestamp = strtotime($checkin_date_time);
                    $formatedCheckinDateTime = date("Y-m-d h:i A", $timestamp);

                    $intervalSeconds = $interval->s + $interval->i * 60 + $interval->h * 3600 + $interval->d * 86400;

                    $IntervalHrs= $intervalSeconds/3600;
                    // dd($IntervalHrs>=8);
                    if($IntervalHrs>=8)
                    {
                        $checkoutDateTime = clone $checkinDateTime; // Create a copy to avoid modifying the original object
                        $checkoutDateTime->modify('+8 hours');

                        // Add 8 hours to the date component
                        // Separate date and time
                        $checkoutDate = $checkoutDateTime->format('Y-m-d');
                        $checkoutTime = $checkoutDateTime->format('H:i:s');

                        $recordArray=[
                            'date'=>$checkoutDate,
                            'time'=> $checkoutTime,
                            'status'=> 'check-out',
                            'gps_location'=> $lastTimeSheetRecord->gps_location
                        ];

                        $time_sheet->signature=null;
                        $timeSheetRecord = $time_sheet->timeSheetRecords()->create($recordArray);

                        // dd($time_sheet);


                        // $timeSheetRecord->date= $checkoutDate;
                        // $timeSheetRecord->time= $checkoutTime;
                        // $timeSheetRecord->status='check-out';
                    }


                }
                # code...
            # code...
        }
        // dd($timesheets);
        return $timesheets;

    }

    public function index()
    {
        $pageConfigs = ['pageSidebar' => 'merchandiser-timeSheet'];

        $user= Auth::user();
        $userTimeZone  = $user->time_zone;

        $merchandiserArray = array();
        $compnay_users = $user->companyUser->company->companyUsers;
        $userArr = array();
        foreach ($compnay_users as $key => $compnay_user) {
            if($compnay_user->user->hasRole('merchandiser')){
                array_push($userArr, $compnay_user->user)  ;
            }
        }
        // dd($userArr);
        $stores= $user->companyUser->company->stores;
        $products = [];
        foreach ($stores as $store) {
       //     $storeProducts = $store->products->pluck('id')->toArray(); // Pluck product IDs
        //    $products = array_merge($products, $storeProducts); // Merge product IDs
        }
        $products = Product::whereIn('id', $products)->get();

        $categories = [];

        foreach ($products as $product) {
            $productCategories = $product->category->pluck('id')->toArray(); // Pluck product IDs
            $categories = array_merge($categories, $productCategories); // Merge product IDs
        }
        $categories = Category::whereIn('id', $categories)->get();
        // dd($categories);
        foreach ($compnay_users as $key => $compnay_user) {
            $merchandiser_user = $compnay_user->user;
            $timeSheetArray=array();
            $pendingTimeSheetArr=array();
            if ($merchandiser_user) {
                $userRoles = $merchandiser_user->roles; // Retrieve all roles for the user
                if ($userRoles->count() > 0) {
                    foreach ($userRoles as $role) {
                        $roleName = $role->name;
                        if($roleName == 'merchandiser'){
                            $time_sheets = $merchandiser_user->companyUser->timeSheets;

                        //    $time_sheets= $this->checkTimeSheetStatus($time_sheets);

                            if($time_sheets && $time_sheets->count() > 0){
                                foreach ($time_sheets as $key => $time_sheet) {
                                    $time_sheet->created_at = convertToTimeZone($time_sheet->created_at, 'UTC', $userTimeZone);
                                    $time_sheet->date_modified = convertToTimeZone($time_sheet->date_modified, 'UTC', $userTimeZone);
                                    $checkoutFound = false; // Flag to check if "check-out" status is found
                                    foreach ($time_sheet->timeSheetRecords as $key => $timeSheetRecord) {
                                        if($timeSheetRecord->status=="check-out")
                                        {
                                            array_push($timeSheetArray, $time_sheet);
                                            $checkoutFound = true;
                                            break; // Break the loop if "check-out" status is found
                                        }
                                    }
                                    if (!$checkoutFound) {

                                        if($time_sheet->timeSheetRecords->count() > 0)
                                        {
                                            array_push($pendingTimeSheetArr, $time_sheet);
                                        }
                                    }
                                    # code...
                                }
                                array_push($merchandiserArray, ['id'=>$merchandiser_user->id,'name'=>$merchandiser_user->name, 'role'=>$roleName, 'time_sheets'=>$timeSheetArray, "pending_time_sheets"=>$pendingTimeSheetArr]);
                            }
                        }
                    }
                }
            }
        }
        return view('manager.merchandiserTimeSheet', compact('merchandiserArray','user','userArr', 'stores','products', 'categories'), ['pageConfigs' => $pageConfigs]);
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

    // function getDataByStore(Request $request)
    // {
    //     $SelectedStoreId = $request->value;

    //     $user= Auth::user();
    //     $merchandiserArray = array();
    //     $compnay_users = $user->companyUser->company->companyUsers;
    //     $stores= $user->companyUser->company->stores;
    //     foreach ($compnay_users as $key => $compnay_user) {
    //         $user = $compnay_user->user;
    //         $timeSheetArray=array();
    //         $pendingTimeSheetArr=array();
    //         if ($user) {
    //             $userRoles = $user->roles; // Retrieve all roles for the user
    //             if ($userRoles->count() > 0) {
    //                 foreach ($userRoles as $role) {
    //                     $roleName = $role->name;
    //                     if($roleName == 'merchandiser'){
    //                         $time_sheets = $user->companyUser->timeSheets;
    //                         if($time_sheets && $time_sheets->count() > 0){
    //                             foreach ($time_sheets as $key => $time_sheet) {

    //                                 $checkoutFound = false; // Flag to check if "check-out" status is found
    //                                 foreach ($time_sheet->timeSheetRecords as $key => $timeSheetRecord) {
    //                                     if($timeSheetRecord->status=="check-out")
    //                                     {
    //                                         array_push($timeSheetArray, $time_sheet);
    //                                         $checkoutFound = true;
    //                                         break; // Break the loop if "check-out" status is found
    //                                     }
    //                                 }
    //                                 if (!$checkoutFound) {

    //                                     if($time_sheet->timeSheetRecords->count() > 0)
    //                                     {
    //                                         array_push($pendingTimeSheetArr, $time_sheet);
    //                                     }
    //                                 }
    //                                 # code...
    //                             }
    //                             $selectedTimeSheetArrayByStore= [];
    //                             foreach ($timeSheetArray as $key => $timesheet) {
    //                                 if($time_sheet->storeLocation->store->id==$SelectedStoreId)
    //                                 {
    //                                     array_push($selectedTimeSheetArrayByStore,$time_sheet);
    //                                 }
    //                                 else
    //                                 {

    //                                 }
    //                                     # code...
    //                             }
    //                             array_push($merchandiserArray, ['id'=>$user->id,'name'=>$user->name, 'role'=>$roleName, 'time_sheets'=>$selectedTimeSheetArrayByStore, "pending_time_sheets"=>$pendingTimeSheetArr]);
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     return (response()->json($merchandiserArray));
    // }
}
