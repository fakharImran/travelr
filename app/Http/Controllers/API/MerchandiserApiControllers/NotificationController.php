<?php

namespace App\Http\Controllers\API\MerchandiserApiControllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\UserNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController;

class NotificationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = UserNotification::select('*')->where('user_id',$user->id )->orderBy('created_at', 'desc')->get();
        $arr = array();
        foreach ($notifications as $key => $value) {
            $temp =  $value->notification;
            unset($temp['user_ids']);
            array_push($arr,   $temp);
        }
        
        if($notifications)
        {
            return $this->sendResponse(['notifications'=>$arr], 'notifications exist');
        }
        else
        {
            return $this->sendResponse(['notifications'=>$arr, 'user'=>$user], 'no notifications exist');

        }
    }
    function getNotificationByDate($date)
    {
        $user = Auth::user();
        $notifications = UserNotification::select('*')->where('user_id',$user->id )->whereRaw("DATE(created_at) = ?", [$date])->orderBy('created_at', 'desc')->get();
        $arr = array();
        foreach ($notifications as $key => $value) {
            $temp =  $value->notification;
            unset($temp['user_ids']);
            array_push($arr,   $temp);
        }

        return $this->sendResponse(['date'=>$date, 'notifications'=>$arr], 'this is the Notification date Data');
        
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
}
