<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;

use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CompanyUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageSidebar' => 'user'];    
        $users= CompanyUser::select('*')->get();
        
        $currentUser = Auth::user();
        $userTimeZone  = $currentUser->time_zone;

        foreach ($users as $key => $companyUser) {
            $companyUser->created_at = convertToTimeZone($companyUser->created_at, 'UTC', $userTimeZone);
            $companyUser->date_modified = convertToTimeZone($companyUser->date_modified, 'UTC', $userTimeZone);
            if($companyUser->last_login_date_time != ''){
                $companyUser->last_login_date_time = convertToTimeZone($companyUser->last_login_date_time, 'UTC', $userTimeZone);
            }
        }

        return view('admin.user.index', compact('users' ), ['pageConfigs' => $pageConfigs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageSidebar' => 'user'];    

        $roles = Role::pluck('name','name')->except('admin');
        $companies= Company::select('*')->get();
        return view('admin.user.create', compact('companies', 'roles'), ['pageConfigs' => $pageConfigs]);
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
        $this->validate($request, [
            'company_id' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'access_privilege' => 'required'
        ]);
        $input = $request->only(['name', 'email', 'password'] );
        $input['password'] = Hash::make($input['password']);
    
       
        $user = User::create($input);
        $tempRole=array();
       
        if($request->access_privilege=="Deactivated")
        {
            $user->assignRole('no_access');

        }
        else
        {
            if($request->input('roles')[0]=="Merchandiser & Manager")
            {
                $tempRole[0] = 'manager';
                $tempRole[1] = 'merchandiser';
                $user->assignRole($tempRole);
            }
            else
            {
                $user->assignRole($request->input('roles'));
            }
        }
        $tempUser= new CompanyUser();
        $tempUser->company_id= $request->company_id;
        $tempUser->user_id=  $user->id;
        $tempUser->access_privilege= $request->access_privilege;
        $tempUser->last_login_date_time=  "";
        $tempUser->date_modified=  date("Y-m-d h:i:s A");
        // dd($tempUser);
        $tempUser->save();
        return redirect()->route('user.index')->with('success','User created successfully');
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
        $pageConfigs = ['pageSidebar' => 'user'];   

        $companies= Company::select('*')->get();
        $companyUser= CompanyUser::select()->where('id',$id)->first(); 
        $company = $companyUser->company;
        $roles = Role::pluck('name','name')->except('admin');
        $user = $companyUser->user;
        $userRole = $user->roles->pluck('name','name')->all();
        return view('admin.user.edit', compact('user', 'userRole', 'roles','id', 'companyUser','company','companies'), ['pageConfigs' => $pageConfigs]);
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
        $companyUser = CompanyUser::where('id', $id)->first();
        $loginNeedToSet= 'NA';
        
        $this->validate($request, [
            'company_id' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$companyUser->user_id,
            'password' => 'same:confirm-password',
            'roles' => 'required',
            'access_privilege' => 'required'
        ]);

        $input = $request->only(['name', 'email', 'password'] );
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
        $user = User::find($companyUser->user_id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$companyUser->user_id)->delete();

        $tempRole=array();
        if($request->access_privilege=="Deactivated")
        {
            $user->assignRole('no_access');

        }
        else
        {
            if($request->input('roles')[0]=="Merchandiser & Manager")
            {
                $tempRole[0] = 'manager';
                $tempRole[1] = 'merchandiser';
                $user->assignRole($tempRole);
            }
            else
            {
                $user->assignRole($request->input('roles'));
            }
        }
       


        $companyUser->company_id= $request->company_id;
        $companyUser->user_id=  $user->id;
        $companyUser->access_privilege= $request->access_privilege;
        $companyUser->date_modified=  date("Y-m-d h:i:s A");

        $companyUser->save();

        return redirect()->route('user.index')->with('success','User updated successfully');;
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
            $item = CompanyUser::find($id);
            $userID=$item->user->id;
            $user = User::find($userID);

            if ($user) {
                $user->delete();
                return redirect()->route('user.index');
            } else {
                return redirect()->back()->withErrors(['error' => 'Item not found']);
                // return response()->json(['error' => 'Item not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong while deleting the item']);
        }
    }


//     public function delete( $id) {
//         try {
//             // Find the item with the given ID and delete it
//             $item = CompanyUser::find($id);
//             if ($item) {
//                 $item->delete();
//                 return redirect()->route('user.index');
//             } else {
//                 return redirect()->back()->withErrors(['error' => 'Item not found']);
//                 // return response()->json(['error' => 'Item not found']);
//             }
//         } catch (\Exception $e) {
//             return response()->json(['error' => 'Something went wrong while deleting the item']);
//         }
//   }
   
}
