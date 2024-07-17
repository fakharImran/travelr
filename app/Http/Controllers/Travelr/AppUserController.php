<?php

namespace App\Http\Controllers\Travelr;

use Validator;
use App\Models\User;
use App\Models\AppUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AppUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $pageConfigs = ['pageSidebar' => 'user'];
        $appUsers= AppUser::select('*')->get();
        $currentUser = Auth::user();
        $userTimeZone  = $currentUser->time_zone;

        foreach ($appUsers as $key => $appUser) {
            $appUser->created_at = convertToTimeZone($appUser->created_at, 'UTC', $userTimeZone);
            $appUser->updated_at = convertToTimeZone($appUser->updated_at, 'UTC', $userTimeZone);
        }


        return view('travelr.appUser.index', compact('appUsers'), ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageSidebar' => 'user'];
        return view('travelr.appUser.create', ['pageConfigs' => $pageConfigs]);

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
           //
           $pageConfigs = ['pageSidebar' => 'user'];

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
           $user = new User($arr);
           $user->save();

           $user->appUser()->create(['first_name'=>$arr['first_name'], "last_name"=>$arr['last_name']]);
           return redirect()->route('appuser.index')->with(['pageConfigs' => $pageConfigs]);
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
        $pageConfigs = ['pageSidebar' => 'user'];

        $appUserData= AppUser::select()->where('id',$id)->first();

        return view('travelr.appuser.edit', compact('appUserData', 'id'), ['pageConfigs' => $pageConfigs]);

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
        $updatedData= array_shift($data);
        $updatedData= array_shift($data);
        $updatedData= array_pop($data);


        $arr =  $data;
        $arr['name'] = $arr['first_name'] . " " . $arr['last_name'];
        $newarr= array_shift($arr);
        $newarr= array_shift($arr);


        $appuser =  AppUser::where('id', $id)->update(['first_name'=>$data['first_name'], "last_name"=>$data['last_name']]);
        $appuser =  AppUser::where('id', $id)->first();
        $appuser->user()->update($arr);


        return redirect()->route('appuser.index');
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
            $item = AppUser::find($id);
            if ($item) {
                $item->user()->delete();
                $item->delete();
                return redirect()->route('appuser.index');
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
