<?php

namespace App\Http\Controllers\Admin\CustomerControllers;
use Validator;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\StoreLocation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageSidebar' => 'notification'];

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

        $name=$user->name;
        $userTimeZone  = $user->time_zone;
        $allNotifications= Notification::all();

        $currentUser = Auth::user();
        $userTimeZone  = $currentUser->time_zone;

        foreach ($allNotifications as $key => $notification) {
            $notification->created_at = convertToTimeZone($notification->created_at, 'UTC', $userTimeZone);
            $notification->date_modified = convertToTimeZone($notification->date_modified, 'UTC', $userTimeZone);
        }

        return view('manager.notifications', compact('userTimeZone','allNotifications','userArr', 'name',  'stores','allLocations','categories', 'products'), ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageSidebar' => 'notification'];
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
            $storeProducts = $store->products->pluck('id')->toArray(); // Pluck product IDs
            $products = array_merge($products, $storeProducts); // Merge product IDs
        }
        $products = Product::whereIn('id', $products)->get();
        $name=$user->name;
        return view('manager.modal.createNotification', compact('userArr', 'name',  'stores','allLocations', 'products'),['pageConfigs' => $pageConfigs]);
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
        $pageConfigs = ['pageSidebar' => 'notification'];

        $validator = Validator::make($request->all(), [
            'user_id'=>'required',
            'title'=>'required',
            'message'=>'required',
        ]);
        if ($validator->fails()) {
            // Validation failed
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($request->hasFile('attachment'))
        {
            $url = $request->file('attachment')->store('notifications', 'public');
        }
        else
        {
            $url=null;
        }

        $data= $request->input();
        $notification = new Notification;

        $notification->store_location_id =$data['store_location_id'];
        $notification->store_id =$data['store_id'];
        $notification->user_ids =json_encode($data['user_id']);
        $notification->title =$data['title'];
        $notification->message =$data['message'];
        $notification->attachment =$url;
        $notification->save();

        foreach ($data['user_id'] as $key => $value) {
            $notification->userNotification()->create(['user_id'=>$value, 'notification_id'=>$notification->id]);
        }


        $fcm_url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = User::whereNotNull('device_token')->whereIn('id', $data['user_id'])->pluck('device_token')->all();
        $serverKey = 'AAAAZ7dCL_c:APA91bEp8yX6CiX_Jxj0iHC0tdR4Bow6maEr0Lv3vluMlSdv-XdJfVYMAlW_5ZqWYSTl0go1Iut7vx4fZYQl8XlgNJgp6COt35fhpwy4UdyQeGHz9Gi1beoRewEOeLzCB1OpRQU20S2h';
        $baseUrl = Config::get('app.url');
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $data['title'],
                "body" => $data['message'],
            ],
            "data"=>[
                "type"=>"msj",
                "title"=> $data['title'],
                "message"=> $data['message'],
                // "image_url"=> $baseUrl.'/'.$url,
                "image_url"=>$url,
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

    // dd($fcm_url , $FcmToken,$data ,$encodedData,  $headers);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $fcm_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        // dd($result);

        return redirect()->route('web_notification.index')->with(['pageConfigs' => $pageConfigs]);

        // return view('manager.notifications', compact('allNotifications','userArr', 'name',  'stores','allLocations', 'products'), ['pageConfigs' => $pageConfigs]);

        //
    }
    function createNotification()
    {

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
        // dd($id);
        $pageConfigs = ['pageSidebar' => 'notification'];

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
            $storeProducts = $store->products->pluck('id')->toArray(); // Pluck product IDs
            $products = array_merge($products, $storeProducts); // Merge product IDs
        }
        $products = Product::whereIn('id', $products)->get();
        $name=$user->name;
        $userTimeZone  = $user->time_zone;
        $selectedNotification= Notification::select()->where('id',$id)->first();
        // dd($selectedNotification);
        return view('manager.modal.editNotification', compact('userTimeZone','selectedNotification','userArr', 'name',  'stores','allLocations', 'products'), ['pageConfigs' => $pageConfigs]);
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
        // dd($request->all());
        $pageConfigs = ['pageSidebar' => 'notification'];
        // dd($request->all());
        $pageConfigs = ['pageSidebar' => 'notification'];

        $validator = Validator::make($request->all(), [
            'store_id'=> 'required',
            'user_id'=>'required',
            'title'=>'required',
            'message'=>'required',
            'store_location_id'=>'required',
        ]);
        if ($validator->fails()) {
            // Validation failed
            return redirect()->back()->withErrors($validator)->withInput();
        }

          // Check for a new file
          if($request->hasFile('attachment'))
          {
              $url = $request->file('attachment')->store('notifications', 'public');
          }
          else
          {
              $url=null;
          }

        // Retrieve existing notification
        $notification = Notification::findOrFail($id);
        $data=$request->all();
        // Update notification attributes
        $notification->store_location_id =$data['store_location_id'];
        $notification->store_id =$data['store_id'];
        $notification->user_ids =json_encode($data['user_id']);
        $notification->title =$data['title'];
        $notification->message =$data['message'];

        // Update attachment if a new file was uploaded
        if ($url!=null) {
            $notification->attachment = $url;
        }

        // Save and update notification
        $notification->update();

        return redirect()->route('web_notification.index')->with(['pageConfigs' => $pageConfigs]);
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
            $item = Notification::find($id);
            if ($item) {
                $item->delete();
                return redirect()->route('web_notification.index');
            } else {
                return redirect()->back()->withErrors(['error' => 'Item not found']);
                // return response()->json(['error' => 'Item not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong while deleting the item']);
        }

        //
    }
}
