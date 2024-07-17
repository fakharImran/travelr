<?php

namespace App\Http\Controllers\Travelr;
use Validator;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageSidebar' => 'setting'];
        $currentUser = Auth::user();
        $userTimeZone  = $currentUser->time_zone;

        // foreach ($currentUser as $key => $appUser) {
        //     $appUser->created_at = convertToTimeZone($appUser->created_at, 'UTC', $userTimeZone);
        //     $appUser->updated_at = convertToTimeZone($appUser->updated_at, 'UTC', $userTimeZone);
        // }


        return view('travelr.setting.edit', compact('currentUser'), ['pageConfigs' => $pageConfigs]);
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

        $pageConfigs = ['pageSidebar' => 'setting'];

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => 'required|same:confirm-password',
            // 'time_zone' =>'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user=$request->except('_token', '_method', 'confirm-password');
 
        $user =  User::where('id', $id)->update($user);

        return redirect()->route('dispatch.index')->with(['pageConfigs' => $pageConfigs]);

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
