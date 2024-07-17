
<!-- Add Contract Modal -->
<div class="modal fade" id="pendingTimeSheet" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-edit-user">
    <div class="modal-content" style="width: 100%; height:100%;">
      <div class="modal-header bg-transparent">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pb-5 px-sm-5 pt-50">
        <div class="text-center mb-2">
          <h1 class="mb-1">Pending Time Sheets</h1>
        </div>
        <div class="table-responsive mt-2" style="overflow: auto;">

            <table id="dataTable" style="border: 1px solid #ccc; min-width: 1580px; ">
                <thead>
                    <tr>
                        <th>Name of Store</th>
                        <th>Location</th>
                        <th>Check-in Time</th>
                        <th>Check-in GPS Location</th>
                        <th>Break Time</th>
                        <th>Lunch Time</th>
                        <th>Check-out Time</th>
                        <th>Check-out GPS Location</th>
                        <th>Hours Worked</th>
                        <th>Merchandiser</th>
                        <th>Store Manager</th>
                        <th>Signature</th>
                        <th>Time of Signature</th>
                    </tr>
                </thead>
                <tbody>
                    
                    {{-- {{dd($merchandiserArray)}} --}}
                   @php
                        $totalHourworked=0;
                   @endphp
                    @foreach ($merchandiserArray as $merchandiser)
                        {{-- {{dd($merchandiser['time_sheets'])}} --}}
                        @foreach ($merchandiser['pending_time_sheets'] as $merchandiser_time_sheet)
        
                            @php
                                $manager = $merchandiser_time_sheet->store_manager_name;
                                
        
                                $checkin_date_time = 'N/A';
                                $checkin_location = 'N/A';
                                $lunch_date_time = 'N/A';
                                $break_date_time = 'N/A';
                                $checkout_date_time = 'N/A';
                                $checkout_location = 'N/A';
                            @endphp
                            @foreach ($merchandiser_time_sheet->timeSheetRecords as $time_sheet_record)
                            
                                @switch($time_sheet_record->status)
                                    @case('check-in')
                                        @php
                                            $checkin_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                            $checkin_location = $time_sheet_record->gps_location;
                                        @endphp
                                        @break
                                    @case('lunch')
                                        @php
                                            $lunch_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                        @endphp
                                        @break
                                    @case('break')
                                        @php
                                            $break_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                        @endphp
                                        @break
                                    @case('check-out')
                                        @php
                                            $checkout_date_time = $time_sheet_record->date . ' ' . $time_sheet_record->time;
                                            $checkout_location = $time_sheet_record->gps_location;
        
        
                                        @endphp
                                        @break
                                    @default
                                        
                                @endswitch
                            @endforeach
        
                            @php
                                
                            @endphp         
                            <tr>
        
                                @if ($merchandiser_time_sheet->store($merchandiser_time_sheet->store_id))
                                    <td  class="tdclass">{{$merchandiser_time_sheet->store($merchandiser_time_sheet->store_id)->name_of_store}}</td>
                                @else
                                    <td  class="tdclass"></td>
                                @endif
                                @if ($merchandiser_time_sheet->store_location($merchandiser_time_sheet->store_location_id))
                                    <td  class="tdclass">{{($merchandiser_time_sheet->store_location($merchandiser_time_sheet->store_location_id)->location)??null}}</td>
                                @else
                                    <td  class="tdclass"></td>
                                @endif
                                <td>{{$checkin_date_time}}</td>
                                <td>{{$checkin_location}}</td>
                                <td>{{$break_date_time}}</td>
                                <td>{{$lunch_date_time}}</td>
                                <td>{{$checkout_date_time}}</td>
                                <td>{{$checkout_location}}</td>
                                <td>N/A</td>
                                {{-- <td>{{}}</td> --}}
                                <td>{{$merchandiser['name']}}</td>
                                <td>{{$manager}}</td>
                                <td>N/A</td>
                                <td>N/A</td>
                            </tr>
        
                            {{-- {{dd('umer')}} --}}
                        @endforeach
                        
                    @endforeach
                    {{-- {{dd($totalHourworked)}} --}}
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>