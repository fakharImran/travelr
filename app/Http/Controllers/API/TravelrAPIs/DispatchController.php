<?php

namespace App\Http\Controllers\API\TravelrAPIs;

use Validator;
use Carbon\Carbon;
use App\Models\Dispatch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DispatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

    }

    public function updateDriverTimeAwayonDispatchSheet(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'time_away' => 'required|integer',
        ]);
    
        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }
    
        $dispatchId = $request->input('dispatch_id');
        $driverId = $request->input('driver_id');
    
        $dispatch = Dispatch::find($dispatchId);
    
        if ($dispatch) {
            if ($dispatch->driver_id) {

                $dispatch->time_away = $request->time_away;
                $dispatch->save();
                return response()->json(['success' => false, 'message' => 'Dispatch time away is updated'], 500);
            }
            else{
                return response()->json(['success' => false, 'message' => 'Selected Dispatch does not have a driver assigned'], 500);

            }
    
           
    
            return response()->json(['success' => true, 'message' => 'Driver assigned to dispatch successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Dispatch not found'], 404);
        }
    }

    public function confirmRide(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'dispatch_id' => 'required|integer|exists:dispatches,id',
            'driver_id' => 'required|integer|exists:drivers,id',
        ]);
    
        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation Error', 'errors' => $validator->errors()], 422);
        }
    
        $dispatchId = $request->input('dispatch_id');
        $driverId = $request->input('driver_id');
    
        $dispatch = Dispatch::find($dispatchId);
    
        if ($dispatch) {
            if ($dispatch->driver_id) {
                return response()->json(['success' => false, 'message' => 'assigned_already'], 409);
            }
    
            $dispatch->driver_id = $driverId;
            $dispatch->status = "in-progress";
            $dispatch->save();
    
            return response()->json(['success' => true, 'message' => 'assigned']);
        } else {
            return response()->json(['success' => false, 'message' => 'Dispatch not found'], 404);
        }
    }
    

    public function getDispatch(Request $request)
    {
          // Validate the request to ensure 'id' is present
          $request->validate([
            'id' => 'required|integer',
        ]);

        // Retrieve the dispatch_sheet with their dispatches using the provided id
        $dispatch_sheet_id = $request->query('id');
        $dispatch_sheet = Dispatch::with('driver')->find($dispatch_sheet_id);

        // Check if the driver was found
        if ($dispatch_sheet) {
            return response()->json(['success' => true, 'dispatch_sheet' => $dispatch_sheet], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Dispatch Sheet not found'], 404);
        }
    }

    public function getAllDispatch(Request $request)
    {
        

        $allDispatches= Dispatch::all();

        if ($allDispatches) {
            return response()->json(['success' => true, 'All Dispatch Sheets' => $allDispatches], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Dispatch Sheet not found'], 404);
        }
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
        
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

       
    }



    public function updateDispatchStatus(Request $request)
    {
        // Validate the incoming request data
        $dispatchId = $request->input('dispatch_id');
        $status = $request->input('status');

        $dispatch = Dispatch::find($dispatchId);

        if ($dispatch) {
            $dispatch->status = $status;
            $dispatch->save();

            return response()->json(['success' => true, 'message' => 'Dispatch status updated successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Dispatch not found'], 404);
        }
    }
}
