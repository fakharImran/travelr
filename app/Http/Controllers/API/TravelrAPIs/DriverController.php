<?php

namespace App\Http\Controllers\API\TravelrAPIs;

use Validator;

use App\Models\User;
use App\Models\Driver;
use App\Models\Dispatch;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\API\BaseController as BaseController;

class DriverController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         //
        $user = Auth::user();         

        return response()->json(['user' => $user, 'message' => 'User index'], 201);
    }

    // public function getDriverDispatchData($id=0)
    // {
    //     return" in function dispatch";
    //     $dispatchData= Dispatch::select('*')->where('id',$id)->get();
    //     return response()->json(['DispatchData' => $dispatchData, 'message' => 'here is your dispatch data'], 201);

    // }

    public function getDriver(Request $request)
    {
        // Validate the request to ensure 'id' is present
        $request->validate([
            'id' => 'required|integer',
        ]);

        // Retrieve the driver with their dispatches using the provided id
        $driverId = $request->query('id');
        // $user= User::with('driverUser')->find($userId);
            $driver = Driver::with('dispatches')->find($driverId);
            $dispatches= $driver->dispatches;

                // Check if the driver was found
        if ($driver) {

            $success['id'] =  $driver->id;
            $success['name'] =  $driver->user->name;
            $success['email'] =  $driver->user->email;
            $success['phone_no'] =  $driver->user->phone_no;
            $success['dispatches'] = $dispatches;
            return $this->sendResponse($success, ' Driver Details with dispatches.');
            
        } else {
            return response()->json(['success' => false, 'message' => 'Driver not found'], 404);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_no' => 'required',
            'email' => 'required|email|unique:users,email',
            'time_zone' => 'required',
            'device_token' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        
        if ($validator->fails()) {
            return $this->sendResponse(['error' => $validator->errors()], 'Validation Error', 422);
        }
        
            //   $input = $request->all();
            // $input['password'] = bcrypt($input['password']);


        $arr = $request->only(['first_name', 'last_name', 'phone_no', 'email', 'password', 'time_zone', 'device_token']);
        $arr['name'] = $arr['first_name'] . " " . $arr['last_name'];
        
        $user = User::create([
            'name' => $arr['name'],
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'time_zone' => $request->time_zone,
            'device_token' => $request->device_token,
            'password' => Hash::make( $request->password), // Make sure to hash the password
        ]);
        
        $driverRole = Role::findByName('driver');
        $user->assignRole($driverRole);

        // Assuming driverUser() is a relationship on the User model
        $user->driverUser()->create([
            'first_name' => $arr['first_name'],
            'last_name' => $arr['last_name']
        ]);
        $driverDetails=[
                        'driver_id' => $user->driverUser->id,
                        'driver_name' => $user->name,
                        'phone_no' => $user->phone_no,
                        'email' => $user->email,
                        'time_zone' => $user->time_zone,

                    ]; 
        return response()->json(['driverDetails' => $driverDetails, 'message' => 'Driver created successfully'], 201);
    }


public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
        'time_zone'=> 'required',
        'device_token' => 'required',
    ]);

    if($validator->fails()){
        return $this->sendError('Validation Error.', $validator->errors());       
    }

    if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
        $user = Auth::user(); 
        if($user->hasRole('driver')){
            //update last login date and time
            $user->update(['time_zone' => $request->input('time_zone'), 'device_token' => $request->input('device_token')]);

            $success['token'] = $user->createToken('api-token')->plainTextToken;
            $success['name'] =  $user->name;
            $success['id'] =  $user->driverUser->id;
            $success['device_token'] = $user->device_token;
            return $this->sendResponse($success, 'Driver login successfully.');
        }
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised, Please login with driver credentials.']);
        } 
        
    } 
    else{ 
        return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
    } 
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
    // public function edit( $id)
    // {
    //     //
    //     $pageConfigs = ['pageSidebar' => 'driver'];

    //     $driverUserData= Driver::select()->where('id',$id)->first();

    //     return view('travelr.driverUser.edit', compact('driverUserData', 'id'), ['pageConfigs' => $pageConfigs]);

    // }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'first_name'=>'required',
    //         'last_name'=>'required',
    //         'email'=>'required',
    //         'password' => 'required|same:confirm-password',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }


    //     $data=$request->input();
        
    //     array_shift($data);
    //     array_shift($data);
    //     array_pop($data);


    //     $arr =  $data;
    //     $arr['name'] = $arr['first_name'] . " " . $arr['last_name'];
    //     array_shift($arr);
    //     array_shift($arr);


    //     $driveruser =  Driver::where('id', $id)->update(['first_name'=>$data['first_name'], "last_name"=>$data['last_name']]);
    //     $driveruser =  Driver::where('id', $id)->first();
    //     $driveruser->user()->update($arr);


    //     return redirect()->route('driver.index');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {

    //     try {
    //         // Find the item with the given ID and delete it
    //         $item = Driver::find($id);
    //         if ($item) {
    //             $item->user()->delete();
    //             $item->delete();
    //             return redirect()->route('driver.index');
    //         } else {
    //             return redirect()->back()->withErrors(['error' => 'Item not found']);
    //             // return response()->json(['error' => 'Item not found']);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Something went wrong while deleting the item']);
    //     }

    //     //
    // }
}
