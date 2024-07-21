<?php

namespace App\Http\Controllers\Travelr;

use Validator;

use App\Models\User;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         //
         $pageConfigs = ['pageSidebar' => 'driver'];
         $driverUsers= Driver::select('*')->get();
         $currentUser = Auth::user();
         $userTimeZone  = $currentUser->time_zone;

         foreach ($driverUsers as $key => $appUser) {
             $appUser->created_at = convertToTimeZone($appUser->created_at, 'UTC', $userTimeZone);
             $appUser->updated_at = convertToTimeZone($appUser->updated_at, 'UTC', $userTimeZone);
         }


         return view('travelr.driverUser.index', compact('driverUsers'), ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $pageConfigs = ['pageSidebar' => 'driver'];
        return view('travelr.driverUser.create', ['pageConfigs' => $pageConfigs]);
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
             $pageConfigs = ['pageSidebar' => 'driver'];

             $validator = Validator::make($request->all(), [
                 'first_name'=>'required',
                 'last_name'=>'required',
                 'email'=>'required',
                 'password' => 'required|same:confirm-password',
              ]);
             if ($validator->fails()) {
                 // Validation failed
                 return redirect()->back()->withErrors($validator)->withInput();
             }

             $arr =  $request->input();
             $arr['name'] = $arr['first_name'] . " " . $arr['last_name'];
             $user['password']=    Hash::make( $request->password); // Make sure to hash the password

             $user = new User($arr);
             $user->save();

             $user->driverUser()->create(['first_name'=>$arr['first_name'], "last_name"=>$arr['last_name']]);
             return redirect()->route('driver.index')->with(['pageConfigs' => $pageConfigs]);
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
    public function edit( $id)
    {
        //
        $pageConfigs = ['pageSidebar' => 'driver'];

        $driverUserData= Driver::select()->where('id',$id)->first();

        return view('travelr.driverUser.edit', compact('driverUserData', 'id'), ['pageConfigs' => $pageConfigs]);

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
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required',
            'password' => 'required|same:confirm-password',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $data=$request->input();
        
        array_shift($data);
        array_shift($data);
        array_pop($data);


        $arr =  $data;
        $arr['name'] = $arr['first_name'] . " " . $arr['last_name'];
        array_shift($arr);
        array_shift($arr);


        $driveruser =  Driver::where('id', $id)->update(['first_name'=>$data['first_name'], "last_name"=>$data['last_name']]);
        $driveruser =  Driver::where('id', $id)->first();
        $driveruser->user()->update($arr);


        return redirect()->route('driver.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {
            // Find the item with the given ID and delete it
            $item = Driver::find($id);
            if ($item) {
                $item->user()->delete();
                $item->delete();
                return redirect()->route('driver.index');
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
