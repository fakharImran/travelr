<?php

namespace App\Http\Controllers\API\MerchandiserApiControllers;

use App\Models\Activity;
use App\Models\OutOfStock;
use App\Models\PriceAudit;
use App\Models\Opportunity;
use Illuminate\Http\Request;
use App\Models\MarketingActivity;
use App\Models\StockCountByStores;
use App\Http\Controllers\Controller;
use App\Models\ProductExpiryTracker;
use Illuminate\Support\Facades\Auth;
use App\Models\MerchandiserTimeSheet;
use App\Models\PlanogramComplianceTracker;
use App\Http\Controllers\API\BaseController;

class ActivityController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $activities = $user->companyUser->activities()
            ->orderBy('created_at', 'desc')
            ->select('id', 'activity_description', 'activity_type', 'activity_detail') // Replace 'column1', 'column2', 'column3' with the actual column names you want to select.
            ->get();
        if($activities)
        {
            return $this->sendResponse(['activities'=>$activities], 'activities exist');
        }
        else
        {
            return $this->sendResponse(['activities'=>$activities, 'user'=>$user], 'no activities exist');

        }    
    }
    function getActivityByDate($date)
    {
        $user = Auth::user();

        $company_user = $user->companyUser;
        $activities = Activity::select('*')->where('company_user_id',$company_user->id )
        ->whereRaw("DATE(created_at) = ?", [$date]) 
        ->orderBy('created_at', 'desc')
        ->select('id', 'activity_description', 'activity_type', 'activity_detail') 
        ->get();
        return $this->sendResponse(['date'=>$date, 'activities'=>$activities], 'this is the date Data');
        
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
        $activityData= Activity::find($id);
        $activityDetails= json_decode($activityData->activity_detail);
        switch ($activityData->activity_type) {
            case 'Marketing Activity':
                # code...
                $marketingActivity = MarketingActivity::find($activityDetails->id);

                if ($marketingActivity) {
                    $marketingActivity->delete(); // Delete the record
                    $activityData->delete();
                    return $this->sendResponse(['activityDetails id' => $activityDetails->id], 'Marketing Activity activity deleted successfully.');
                } else {
                    // Handle the case where the data is not found
                    return $this->sendError('Marketing Activity data not found.  Already Deleted');
                }
                break;
            case 'Opportunity':
                # code...
                $Opportunity = Opportunity::find($activityDetails->id);

                if ($Opportunity) {
                    $Opportunity->delete(); // Delete the record
                    $activityData->delete();
                    return $this->sendResponse(['activityDetails id' => $activityDetails->id], 'Opportunity activity deleted successfully.');
                } else {
                    // Handle the case where the data is not found
                    return $this->sendError('Opportunity data not found.  Already Deleted');
                }
                break;
            case 'Out of Stock':
                 # code...
                 $OutOfStock = OutOfStock::find($activityDetails->id);

                 if ($OutOfStock) {
                     $OutOfStock->delete(); // Delete the record
                     $activityData->delete();
                     return $this->sendResponse(['activityDetails id' => $activityDetails->id], 'Out of Stock deleted successfully.');
                 } else {
                     // Handle the case where the data is not found
                     return $this->sendError('Out of Stock data not found.  Already Deleted');
                 }
                break;
            case 'Planogram Compliance Tracker':
                    $PlanogramComplianceTracker = PlanogramComplianceTracker::find($activityDetails->id);
                    // $prevActivityPlanogram= Activity::where('activity_type', 'Planogram Compliance Tracker')
                    // ->where('company_user_id', $activityDetails->id)->get()
                    // ->where('store_id', $activityDetails->store_id);
                    // return $this->sendResponse(['PlanogramComplianceTracker' => $PlanogramComplianceTracker, 'activityData' =>$activityData, 'prevActivityPlanogram' =>$prevActivityPlanogram], 'Planogram Compliance Tracker deleted successfully.');

                    if ($PlanogramComplianceTracker) {
                        $PlanogramComplianceTracker->delete(); // Delete the record
                        $activityData->delete();
                        return $this->sendResponse(['activityDetails id' => $activityDetails->id], 'Planogram Compliance Tracker deleted successfully.');
                    } else {
                        // Handle the case where the data is not found
                        return $this->sendError('Planogram Compliance Tracker data not found. Already Deleted');
                    }
                    break;
            case 'Price Audit':
                 # code...
                 $PriceAudit = PriceAudit::find($activityDetails->id);

                 if ($PriceAudit) {
                     $PriceAudit->delete(); // Delete the record
                     $activityData->delete();
                     return $this->sendResponse(['activityDetails id' => $activityDetails->id], 'Price Audit deleted successfully.');
                 } else {
                     // Handle the case where the data is not found
                     return $this->sendError('Price Audit data not found.  Already Deleted');
                 }
                break;
            case 'Product Expiry':
                 # code...
                 $ProductExpiryTracker = ProductExpiryTracker::find($activityDetails->id);

                 if ($ProductExpiryTracker) {
                     $ProductExpiryTracker->delete(); // Delete the record
                     $activityData->delete();
                     return $this->sendResponse(['activityDetails id' => $activityDetails->id], 'Product Expiry Tracker deleted successfully.');
                 } else {
                     // Handle the case where the data is not found
                     return $this->sendError('Product Expiry Tracker data not found.  Already Deleted');
                 }
                break;
            case 'Stock count':
                # code...
                $stockCount = StockCountByStores::find($activityDetails->id);

                if ($stockCount) {
                    $stockCount->delete(); // Delete the record
                    $activityData->delete();
                    return $this->sendResponse(['activityDetails id' => $activityDetails->id], 'Stock count activity deleted successfully.');
                } else {
                    // Handle the case where the data is not found
                    return $this->sendError('Stock count data not found.  Already Deleted');
                }
                break;
            case 'Merchandiser Timesheet':
                $MerchandiserTimeSheet = MerchandiserTimeSheet::find($activityDetails->id);

                if ($MerchandiserTimeSheet) {
                    $MerchandiserTimeSheet->delete(); // Delete the record
                    $activityData->delete();
                    return $this->sendResponse(['activityDetails id' => $activityDetails->id], 'Merchandiser TimeSheet activity deleted successfully.');
                } else {
                    // Handle the case where the data is not found
                    return $this->sendError('Merchandiser TimeSheet data not found.  Already Deleted');
                }
                break;
            default:
            return $this->sendResponse(['activity_type'=>$activityData->activity_type], 'no activity available on this id.');
            break;
        }
        return $this->sendResponse(['id'=>$id, 'activityDetails'=>$activityDetails], 'you want to delete this id activity');
        //
    }
    function deleteMerchandiser()
    {
        $user = Auth::user();
        if($user->delete())
        {
            return $this->sendResponse(['loginUserData'=>$user], 'you have deleted merchandiser named '.$user->name );
        }
        return $this->sendResponse('you have shered id this' );

        
    }
}
