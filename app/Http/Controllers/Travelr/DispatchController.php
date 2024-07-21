<?php

namespace App\Http\Controllers\Travelr;

use Exception;
use Validator;
use Carbon\Carbon;
use Google\Client;
use App\Models\User;
use App\Models\Driver;
use App\Models\Dispatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use GuzzleHttp\Client as GuzzleClient; 

class DispatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAccessToken($serviceAccountPath) {
        $client = new Client();

        // Set the path to the CA bundle file
        $caCertPath = storage_path('cacert-2024-07-02.pem');
        
    // Set Guzzle options to use the CA bundle file
        $guzzleClient = new GuzzleClient([
            'verify' => $caCertPath,
        ]);

        $client->setHttpClient($guzzleClient);
        $client->setAuthConfig($serviceAccountPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->useApplicationDefaultCredentials();

        $token = $client->fetchAccessTokenWithAssertion();

        return $token['access_token'];
    }
 
 
    public function sendMessage($accessToken, $projectId, $message) {
        $url = 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send';
        $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $message]));
        $response = curl_exec($ch);
        if ($response === false) {
        throw new Exception('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);
        return json_decode($response, true);
    }
 

    public function testfirebase(){
            // Define the path to your service account JSON file
        $serviceAccountPath = storage_path('travelr-2024-king-firebase-adminsdk-dr1aa-f6b631e687.json');



        // try {
        //     // Get access token
        //     $accessToken =  $this->getAccessToken($serviceAccountPath);
        //     echo "Access Token: " . $accessToken;
        // } catch (Exception $e) {
        //     echo 'Error: ' . $e->getMessage();
        // }


        // Get access token
        $accessToken = $this->getAccessToken($serviceAccountPath);
        // dd($accessToken);
        // Define your Firebase project ID
        $projectId = 'travelr-2024-king';
        // Fetch device tokens from related User model through Driver
        $FcmToken = User::whereHas('driverUser', function($query) {
            $query->whereNotNull('device_token');
        })->pluck('device_token')->all();
        // Define the message payload
        $message = [
            'token' => $FcmToken[0],
            'notification' => [
                'title' => 'Test Notification',
                'body' => 'This is a test notification',
            ],
        ];

        try {
            // Send message
            $response = $this->sendMessage($accessToken, $projectId, $message);
            print_r($response);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function index()
    {
        //
        $pageConfigs = ['pageSidebar' => 'dispatch'];
        $todayMidnight = Carbon::today();

        // Fetch records except those created after today's 12:00 AM
        $dispatchs = Dispatch::where('created_at', '>', $todayMidnight)
            ->orderBy('created_at', 'DESC')
            ->get();

        $currentUser = Auth::user();
        $userTimeZone  = $currentUser->time_zone;

        foreach ($dispatchs as $key => $company) {
            $company->created_at = convertToTimeZone($company->created_at, 'UTC', $userTimeZone);
            $company->updated_at = convertToTimeZone($company->updated_at, 'UTC', $userTimeZone);
        }


        return view('travelr.dispatch.index', compact('dispatchs', 'currentUser'), ['pageConfigs' => $pageConfigs]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $pageConfigs = ['pageSidebar' => 'dispatch'];
        return view('travelr.dispatch.create', ['pageConfigs' => $pageConfigs]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'pick_up_address' => 'required|string',
            'drop_off_address' => 'required|string',
            'phone_no' => 'required|string',
            'fare' => 'required|numeric',
            // 'time_away' => 'required|string',
        ]);

        $dispatch = Dispatch::create([
            'pick_up_address' => $request->pick_up_address,
            'drop_off_address' => $request->drop_off_address,
            'phone_no' => $request->phone_no,
            'fare' => $request->fare,
            'time_away' => $request->time_away,
            'status' => "pending",
        ]);

        $dataRequest = $request->input();


                // Path to your service account JSON key file
        $serviceAccountPath = storage_path('travelr-2024-king-firebase-adminsdk-dr1aa-f6b631e687.json');
        
        // Your Firebase project ID
        $projectId = 'travelr-2024-king';

        
        // Fetch device tokens from related User model through Driver
        $FcmTokens = User::whereHas('driverUser', function($query) {
            $query->whereNotNull('device_token');
        })->pluck('device_token')->all();

        if (empty($FcmTokens)) {
            return response()->json(['success' => false, 'message' => 'No device tokens found']);
        }


        $notificationData = [
            "id" => $dispatch->id,
            "pick_up_address" => $dataRequest['pick_up_address'],
            "drop_off_address" => $dataRequest['drop_off_address'],
            "phone_no" => $dataRequest['phone_no'],
            "fare" => $dataRequest['fare'],
        ];
        try {
            $accessToken = $this->getAccessToken($serviceAccountPath);
    
            foreach ($FcmTokens as $token) {
                $message = [
                    'token' => $token,
                    'notification' => [
                        'title' => 'Ride Available for you',
                        'body' => 'New dispatch available.',
                    ],
                    'data' => $notificationData,
                ];
    
                $this->sendMessage($accessToken, $projectId, $message);
            }
    
            return response()->json(['success' => true, 'dispatch' => $dispatch->id]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Notification failed: ' . $e->getMessage()]);
        }
    }

    
    // public function store(Request $request)
    // {
    //     //
    //     $pageConfigs = ['pageSidebar' => 'notification'];

    //     $validator = Validator::make($request->all(), [
    //         'pick_up_address'=>'required',
    //         'drop_off_address'=>'required',
    //         'phone_no'=>'required',
    //         'time_away'=>'required',
    //         'fare'=>'required',
    //     ]);
    //     if ($validator->fails()) {
    //         // Validation failed
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }


    //     $tempCompany= new Dispatch($request->input());
    //     $tempCompany->save();
    //     $dispatchs= Dispatch::select('*')->get();
    //     // dd($dispatchs);
    //     return redirect()->route('dispatch.index')->with(['pageConfigs' => $pageConfigs]);


    // }

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
         // Validate the incoming request data
         $validator = Validator::make($request->all(), [
            'pick_up_address' => 'required|string',
            'drop_off_address' => 'required|string',
            'phone_no' => 'required|string',
            'fare' => 'required|numeric',
            'status' => 'required|string',
        ]);

            if ($validator->fails()) {
                // Validation failed
                return response()->json(['error' => $validator->errors()], 422);
            }

            $updated_at= $request->date . ' ' . $request->time;
            // Find the dispatch by id
            $dispatch = Dispatch::find($id);

            if (!$dispatch) {
                return response()->json(['error' => 'Dispatch not found'], 404);
            }

            // Update the dispatch with the request data
            $dispatch->pick_up_address = $request->input('pick_up_address');
            $dispatch->drop_off_address = $request->input('drop_off_address');
            $dispatch->phone_no = $request->input('phone_no');
            $dispatch->fare = $request->input('fare');
            $dispatch->status = $request->input('status');

            // Save the updated dispatch
            $dispatch->save();

            return response()->json(['success' => 'Dispatch updated successfully', 'dispatch' => $dispatch]);
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
            $item = Dispatch::find($id);
            if ($item) {
                $item->delete();
                return redirect()->route('dispatch.index');
            } else {
                return redirect()->back()->withErrors(['error' => 'Item not found']);
                // return response()->json(['error' => 'Item not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong while deleting the item']);
        }

        //
    }

    public function updateStatus(Request $request)
    {
        // Validate the request data
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|string'
        ]);

        // Find the dispatch record by ID
        $dispatch = Dispatch::find($request->id);

        if ($dispatch) {
            // Update the status
            $dispatch->status = $request->status;
            $dispatch->save();

            // Return a success response
            return response()->json(['success' => true]);
        }

        // Return a failure response if dispatch not found
        return response()->json(['success' => false, 'message' => 'Dispatch not found']);
    }

    public function checkUpdates()
{
    // Get the last updated timestamp of the dispatches table
    $lastUpdate = DB::table('dispatches')->latest('updated_at')->first()->updated_at;

    return response()->json(['last_update' => $lastUpdate]);
}
}
