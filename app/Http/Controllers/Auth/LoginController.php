<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        // dd(Auth::user());


        // Define the mapping of roles to routes
        if(Auth::user()){
            $user = Auth::user();
            // dd($user->role);

            $userRoles = $user->roles->pluck('name')->toArray();

            $roleRoutes = [
                'admin' => '/',
                // 'merchandiser' => '/user-dashboard',
                // Add more roles and their corresponding routes as needed
            ];
            foreach ($userRoles as $role) {
                if (array_key_exists($role, $roleRoutes)) {
                    $this->redirectTo = $roleRoutes[$role];
                    // return redirect($roleRoutes[$role]);
                }
            }
        }
        else{
            Auth::logout();
            $this->redirectTo = '/login';

        }

    }
    protected function authenticated(Request $request, $user)
    {
        $user->update(['time_zone' => $request->input('userTimeZone')]);
        // Rest of the authentication logic
        // Assuming your User model has a 'roles' relationship
        $userRoles = $user->roles->pluck('name')->toArray();

        // Define the mapping of roles to routes
        $roleRoutes = [
            'admin' => '/dispatch',
            'manager' => '/business_overview',
            // 'merchandiser' => '/user-dashboard',
            // Add more roles and their corresponding routes as needed
        ];

        // Find the first matching role route
        foreach ($userRoles as $role) {
            if (array_key_exists($role, $roleRoutes)) {
                if ($role != 'admin') {
                    //update last login date and time
                    $user->companyUser->last_login_date_time =  date("Y-m-d h:i:s A");
                    $user->companyUser->save();
                }
                return redirect($roleRoutes[$role]);
            }
        }

        return redirect('/home'); // Fallback redirection if user has unknown roles
    }
}
