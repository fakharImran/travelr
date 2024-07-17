<?php
namespace App\Services;

use DateTime;
use Carbon\Carbon;
use App\Models\Checkin;
use App\Models\Activity;
use App\Models\MerchandiserTimeSheet;
use App\Models\PlanogramComplianceTracker;

class CheckinService
{

public function processOverdueCheckins()
{
    print("checking process | ");

    $timeSheets = MerchandiserTimeSheet::all();

    foreach ($timeSheets as $timeSheet) {
        print("timesheeet process | ");

        $records = $timeSheet->timeSheetRecords;

        if (count($records) > 0) {
            $lastRecord = $records->last();
            print($lastRecord->status);
            // Check if the last record is not checked out
            if ($lastRecord->status != 'check-out') {
                print("    checkout process | ");
                // Get the check-in date and time
                $checkinDateTime = $records[0]->date . ' ' . $records[0]->time;
                $dateTime = new DateTime($checkinDateTime);

                // Add 8 hours to the check-in time
                $afterEightHrsTime = clone $dateTime;
                $afterEightHrsTime->modify('+8 hours');

                // Get the current date and time
                $currentDateTime = new DateTime();

                print("\n || checkinDateTime is ". $checkinDateTime);
                print("\n and after convertions | dateTime is ".  $dateTime->format('Y-m-d H:i:s'). "\n");
                print(' $afterEightHrsTime '.  $afterEightHrsTime->format('Y-m-d H:i:s'));
                print("\n currentDateTime: ". $currentDateTime->format('Y-m-d H:i:s'));

                // Check if the current date and time is greater than or equal to the check-in time + 8 hours
                if ($currentDateTime >= $afterEightHrsTime) {
                    print("set checkout  process | ");
                    // Perform the checkout process
                    $checkoutDateTime = clone $dateTime;
                    $checkoutDateTime->modify('+8 hours');

                    $recordArray = [
                        'date' => $checkoutDateTime->format('Y-m-d'),
                        'time' => $checkoutDateTime->format('H:i:s'),
                        'status' => 'check-out',
                        'gps_location' => $lastRecord->gps_location,
                    ];
                    // Create a new time sheet record for checkout
                    $timeSheetRecord = $timeSheet->timeSheetRecords()->create($recordArray);
                    print($timeSheetRecord . " | |");

                    // Additional actions...
                    $activity= new Activity;
                    $activity->company_user_id= $timeSheet->company_user_id;
                    $activity->activity_description= 'You are checked out at time '.$currentDateTime->format('Y-m-d H:i:s');
                    $activity->activity_type= 'Merchandiser checkout automatically';
                    $activity->activity_detail= json_encode($recordArray);

                    $activity->save();

                    // Update photo_after_stocking_shelf where store_location_id matches and it's null

                    PlanogramComplianceTracker::where('store_location_id', $timeSheet->store_location_id)
                    ->whereNull('photo_after_stocking_shelf')
                    ->update(['photo_after_stocking_shelf' => 'N/A']);

                    print("Checkout process executed for user ID: " . $timeSheet->company_user_id);
                }
            }
        }
    }
}


// public function processOverdueCheckins()
// {
//     $time = 8;
//     print("checking process | ");

//     //for edit the timesheet if the last visit is not checkout
//     $timeSheets = MerchandiserTimeSheet::all();
//     if ($timeSheets && count($timeSheets) > 0) 
//     {
//         print("timesheeet process | ");
//         foreach ($timeSheets as $key => $timeSheet) {
//             $records = $timeSheet->timeSheetRecords;
//             $recordsCount = count($records);

//             print($records[$recordsCount-1]->status);

//             if($records[$recordsCount-1]->status != 'check-out'){
//                 // foreach ($records as $key => $record) {
//                 //     if($record->status == 'end-break-time'){
//                 //         $time = 8.50;
//                 //         $time_diff = $record->time - $records[$key-1]->time;
                        
//                 //     }

//                 //     if($record->status == 'end-lunch-time'){
                        
//                 //     }
//                 // }
                
//                 print("    checkout process | ");

//                 $checkin = $records[0];
//                 $checkinTime = $checkin->time;
//                 $checkinDate = $checkin->date;
//                 $checkinDateTime =  $checkinDate . " " . $checkinTime;
//                 $dateTime = new DateTime($checkinDateTime);
//                 $formattedDate = $dateTime->format('Y-m-d H:i:s');

//               $afterEightHrsTime=  $dateTime->modify('+8 hours');

//                 // Get the resulting date and time
//                 $afterEightHrsTime = $dateTime->format('Y-m-d H:i:s');


//                 echo $formattedDate; // Output: 2023-12-18 12:31:00
//                     print("<br> || checkinDateTime is ". $checkinDateTime);
//                  print("and after convertions | dateTime is ". $formattedDate);
//                  print(' $afterEightHrsTime '.  $afterEightHrsTime);

//                 if($dateTime <= $afterEightHrsTime){
//                     print("set checkout  process | ");

//                     $checkoutDateTime = clone $dateTime; // Create a copy to avoid modifying the original object
//                     $checkoutDateTime->modify('+8 hours');

//                     // Add 8 hours to the date component
//                     // Separate date and time
//                     $checkoutDate = $checkoutDateTime->format('Y-m-d');
//                     $checkoutTime = $checkoutDateTime->format('H:i:s');
                    
//                     $recordArray=[
//                         'date'=>$checkoutDate,
//                         'time'=> $checkoutTime,
//                         'status'=> 'check-out',
//                         'gps_location'=> $records[$recordsCount-1]->gps_location
//                     ];
                    
//                     print($checkoutDate . " | ");
//                     // $time_sheet->signature=null;
//                     $timeSheetRecord = $timeSheet->timeSheetRecords()->create($recordArray);
//                     print($timeSheetRecord . " | |");

//                     $activity= new Activity;
//                     $activity->store_location_id= $timeSheet->store_location_id;
//                     $activity->store_id= $timeSheet->store_id;
//                     $activity->company_user_id= $timeSheet->company_user_id;
//                     $activity->activity_name= 'Merchandiser checkout automatically';
//                     $activity->activity_detail= json_encode($recordArray);
//                     print($activity);

//                     $activity->save();
                    
//                     // Update photo_after_stocking_shelf where store_location_id matches and it's null

//                     PlanogramComplianceTracker::where('store_location_id', $timeSheet->store_location_id)
//                     ->whereNull('photo_after_stocking_shelf')
//                     ->update(['photo_after_stocking_shelf' => 'N/A']);
//                 }
//             }
//         }
//     }
// }

}
