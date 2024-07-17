<?php

namespace App\Http\Controllers\Travelr;

use Carbon\Carbon;
use App\Models\Dispatch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JobHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $pageConfigs = ['pageSidebar' => 'job_history'];
        // Get today's date at 12:00 AM
        $todayMidnight = Carbon::today();

        // Fetch records except those created after today's 12:00 AM
        $dispatchHistory = Dispatch::where('created_at', '<', $todayMidnight)
            ->orderBy('created_at', 'DESC')
            ->get();

        $currentUser = Auth::user();
        $userTimeZone  = $currentUser->time_zone;

        foreach ($dispatchHistory as $key => $history) {
            $history->created_at = convertToTimeZone($history->created_at, 'UTC', $userTimeZone);
            $history->updated_at = convertToTimeZone($history->updated_at, 'UTC', $userTimeZone);
        }


        return view('travelr.jobHistory.index', compact('dispatchHistory'), ['pageConfigs' => $pageConfigs]);
        

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
