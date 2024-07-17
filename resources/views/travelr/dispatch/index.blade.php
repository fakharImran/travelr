@extends('layouts.app')

@section("top_links")
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --}}

<script>
function formatTime(timeString) {  // this function if just for edit time display only
    // Split the time string into hours, minutes, seconds, and period (AM/PM)
    let [timePart, period] = timeString.split(' ');

    // Split the time part into hours, minutes, and seconds
    let [hours, minutes, seconds] = timePart.split(':');

    // Convert hours to integer
    hours = parseInt(hours);

    // Adjust hours based on AM/PM period
    if (period === 'PM' && hours < 12) {
        hours += 12;
    } else if (period === 'AM' && hours === 12) {
        hours = 0;
    }

    // Pad hours, minutes, and seconds with leading zeros if necessary
    hours = hours.toString().padStart(2, '0');
    minutes = minutes.padStart(2, '0');
    seconds = seconds.padStart(2, '0');

    // Return formatted time in HH:MM:SS format
    return `${hours}:${minutes}:${seconds}`;
}

function formatTimewithAmPm(timeString) { // this function is for disply date properly, 
    // Split the time string into hours, minutes, and seconds

    // alert('just recieve '+ timeString)
    let [hours, minutes, seconds] = timeString.split(':');

    // Convert hours to integer
    hours = parseInt(hours);

    // Determine the period (AM/PM)
    let period = hours >= 12 ? 'PM' : 'AM';

    // Adjust hours based on the period
    if (hours > 12) {
        hours -= 12;
    } else if (hours === 0) {
        hours = 12;
    }

    // Pad hours, minutes, and seconds with leading zeros if necessary
    hours = hours.toString().padStart(2, '0');
    minutes = minutes.padStart(2, '0');
    seconds = seconds.padStart(2, '0');

    // Return formatted time in HH:MM:SS AM/PM format
    return `${hours}:${minutes}:${seconds} ${period}`;
}


    function handleAction(id, action, rowNumber) {
        let parentTableRow = document.getElementById(`dispatch-row-${rowNumber}`);

        if (parentTableRow) {
            // Your code that accesses parentTableRow properties
        } else {
            console.error(`Element with ID 'dispatch-row-${rowNumber}' not found.`);
        }
        if (action === "edit") {
            let date = parentTableRow.querySelector(`#newDate-${rowNumber}`).innerHTML;
            let time = formatTime(parentTableRow.querySelector(`#newTime-${rowNumber}`).innerHTML);
            let pickup = parentTableRow.querySelector(`#newPickUp-${rowNumber}`).innerHTML;
            let dropoff = parentTableRow.querySelector(`#newDropOff-${rowNumber}`).innerHTML;
            let phone = parentTableRow.querySelector(`#newPhone-${rowNumber}`).innerHTML;
            let fare = parentTableRow.querySelector(`#newFare-${rowNumber}`).innerHTML;
            let timeAway = parentTableRow.querySelector(`#newTimeAway-${rowNumber}`).innerHTML;
            let status = parentTableRow.querySelector(`#newStatus-${rowNumber}`).innerHTML;

            

            parentTableRow.innerHTML = `
                <td class="tdclass">${rowNumber}</td>
                <td class="tdclass"><input type="date" class="form-control" id="newDate-${rowNumber}" value="${date}" readonly></td>
                <td class="tdclass"><input type="time" class="form-control" id="newTime-${rowNumber}" value="${time}" readonly></td>
                <td class="tdclass"><input type="text" class="form-control" id="newPickUp-${rowNumber}" value="${pickup}"></td>
                <td class="tdclass"><input type="text" class="form-control" id="newDropOff-${rowNumber}" value="${dropoff}"></td>
                <td class="tdclass"><input type="number" class="form-control" id="newPhone-${rowNumber}" value="${phone}"></td>
                <td class="tdclass"><input type="number" class="form-control" id="newFare-${rowNumber}" value="${fare}"></td>
                <td class="tdclass">
                    <button id="edit-button-${rowNumber}" class="btn btn-warning" style="display: none;" onclick="handleAction(${id}, 'edit', ${rowNumber})">Edit</button>
                    <button id="update-button-${rowNumber}" class="btn btn-success" onclick="handleAction(${id}, 'update', ${rowNumber})">Update</button>
                    <button id="cancel-button-${rowNumber}" class="btn btn-danger" onclick="handleAction(${id}, 'cancel', ${rowNumber})">Cancel</button>
                </td>
                <td class="tdclass"><input type="number" class="form-control" id="newTimeAway-${rowNumber}" value="${timeAway}"></td>
                <td class="tdclass"><input type="text" readonly class="form-control" id="newStatus-${rowNumber}" value="${status}"></td>
            `;
        } else if (action === "update") {

            let date = parentTableRow.querySelector(`#newDate-${rowNumber}`).value;
            let time = formatTimewithAmPm(parentTableRow.querySelector(`#newTime-${rowNumber}`).value);
            let pickup = parentTableRow.querySelector(`#newPickUp-${rowNumber}`).value;
            let dropoff = parentTableRow.querySelector(`#newDropOff-${rowNumber}`).value;
            let phone = parentTableRow.querySelector(`#newPhone-${rowNumber}`).value;
            let fare = parentTableRow.querySelector(`#newFare-${rowNumber}`).value;
            let timeAway = parentTableRow.querySelector(`#newTimeAway-${rowNumber}`).value;
            let status = parentTableRow.querySelector(`#newStatus-${rowNumber}`).value;

            let allvalues = {
                date: date,
                time: time,
                pick_up_address: pickup,
                drop_off_address: dropoff,
                phone_no: phone,
                fare: fare,
                time_away: timeAway,
                status: status
            };
            $.ajax({
               url: `{{ url('/dispatch/update') }}/${id}`, // Correctly interpolate the URL
                type: 'PUT', // Use PUT method
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token in headers
                },
                data: allvalues
                ,
                success: function(response) {
                    if (response.success) {
                        // Update the table row with the updated data
                        let parentTableRow = document.getElementById(`dispatch-row-${rowNumber}`);
                        parentTableRow.innerHTML = `
                            <td class="tdclass">${rowNumber}</td>
                            <td class="tdclass" id="newDate-${rowNumber}">${date}</td>
                            <td class="tdclass" id="newTime-${rowNumber}">${time}</td>
                            <td class="tdclass" id="newPickUp-${rowNumber}">${pickup}</td>
                            <td class="tdclass" id="newDropOff-${rowNumber}">${dropoff}</td>
                            <td class="tdclass" id="newPhone-${rowNumber}">${phone}</td>
                            <td class="tdclass" id="newFare-${rowNumber}">${fare}</td>
                            <td class="tdclass">
                                <button id="edit-button-${rowNumber}" class="btn btn-warning" onclick="handleAction(${id}, 'edit', ${rowNumber})">Edit</button>
                                <button id="update-button-${rowNumber}" class="btn btn-success" style="display: none;" onclick="handleAction(${id}, 'update', ${rowNumber})">Update</button>
                                <button id="cancel-button-${rowNumber}" class="btn btn-danger" style="display: none;" onclick="handleAction(${id}, 'cancel', ${rowNumber})">Cancel</button>
                            </td>
                            <td class="tdclass" id="newTimeAway-${rowNumber}">${timeAway}</td>
                            <td class="tdclass" id="newStatus-${rowNumber}">${status}</td>
                        `;
                    } else {
                        alert('Failed to update the dispatch.');
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                    alert('An error occurred while updating the dispatch.');
                }
            });

        } else if (action === "cancel") {
               let time = formatTimewithAmPm(parentTableRow.querySelector(`#newTime-${rowNumber}`).value);

            let originalRow = `
                <td class="tdclass">${rowNumber}</td>
                <td class="tdclass" id="newDate-${rowNumber}">${parentTableRow.querySelector(`#newDate-${rowNumber}`).value}</td>
                <td class="tdclass" id="newTime-${rowNumber}">${time}</td>
                <td class="tdclass" id="newPickUp-${rowNumber}">${parentTableRow.querySelector(`#newPickUp-${rowNumber}`).value}</td>
                <td class="tdclass" id="newDropOff-${rowNumber}">${parentTableRow.querySelector(`#newDropOff-${rowNumber}`).value}</td>
                <td class="tdclass" id="newPhone-${rowNumber}">${parentTableRow.querySelector(`#newPhone-${rowNumber}`).value}</td>
                <td class="tdclass" id="newFare-${rowNumber}">${parentTableRow.querySelector(`#newFare-${rowNumber}`).value}</td>
                <td class="tdclass">
                    <button id="edit-button-${rowNumber}" class="btn btn-warning" onclick="handleAction(${id}, 'edit', ${rowNumber})">Edit</button>
                    <button id="update-button-${rowNumber}" class="btn btn-success" style="display: none;" onclick="handleAction(${id}, 'update', ${rowNumber})">Update</button>
                    <button id="cancel-button-${rowNumber}" class="btn btn-danger" style="display: none;" onclick="handleAction(${id}, 'cancel', ${rowNumber})">Cancel</button>
                </td>
                <td class="tdclass" id="newTimeAway-${rowNumber}">${parentTableRow.querySelector(`#newTimeAway-${rowNumber}`).value}</td>
                <td class="tdclass" id="newStatus-${rowNumber}">${parentTableRow.querySelector(`#newStatus-${rowNumber}`).value}</td>
            `;
            parentTableRow.innerHTML = originalRow;
        }
    }



</script>
<script>

function toggleFullScreen() {
    const fullScreen = document.querySelector('#full-screen');
    const fullScreenButton = document.getElementById('fullScreenButton');
    const exitFullScreenButton = document.getElementById('exitFullScreenButton');
    if (!document.fullscreenElement) {
        fullScreen.requestFullscreen().then(() => {
            fullScreenButton.style.display = 'none';
            exitFullScreenButton.style.display = 'block';
        }).catch(err => {
            console.error(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
        });
    } else {
        document.exitFullscreen().then(() => {
            fullScreenButton.style.display = 'block';
            exitFullScreenButton.style.display = 'none';
        }).catch(err => {
            console.error(`Error attempting to disable full-screen mode: ${err.message} (${err.name})`);
        });
    }
}

</script>

@endsection

@section("bottom_links")
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>


<script>
    document.getElementById('insertButton').addEventListener('click', function() {
    var tableBody = document.getElementById('dispatchTableBody');
    var newRow = document.createElement('tr');

    var rowCount = tableBody.getElementsByTagName('tr').length;
    newRow.id = `dispatch-row-${rowCount + 1}`;

    var now = new Date();
    var currentDate = now.toISOString().split('T')[0]; // Format: YYYY-MM-DD
        var options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
    var diffCurrTime = now.toLocaleTimeString('en-US', options); // Format: HH:MM:SS AM/PM
    
    var currentTime = now.toLocaleTimeString('it-IT'); // Format: HH:MM AM/PM
        newRow.innerHTML = `
            <td class="tdclass">${rowCount + 1}</td>
            <td class="tdclass"><input type="date" class="form-control" id="newDate-${rowCount + 1}" value="${currentDate}"  readonly></td>
            <td class="tdclass"><input type="time" class="form-control" id="newTime-${rowCount + 1}"  value="${currentTime}"   readonly></td>
            <td class="tdclass"><input type="text" class="form-control" id="newPickUp-${rowCount + 1}"></td>
            <td class="tdclass"><input type="text" class="form-control" id="newDropOff-${rowCount + 1}"></td>
            <td class="tdclass"><input type="number" class="form-control" id="newPhone-${rowCount + 1}"></td>
            <td class="tdclass"><input type="number" class="form-control" id="newFare-${rowCount + 1}"></td>
            <td class="tdclass">
                <button class="btn btn-success newSendButton" data-row="${rowCount + 1}">Send</button>
            </td>
            <td class="tdclass"><input type="number" class="form-control" id="newTimeAway-${rowCount + 1}"></td>
            <td class="tdclass"><input type="text" readonly class="form-control" id="newStatus-${rowCount + 1}" value="pending"></td>
        `;
    tableBody.insertBefore(newRow, tableBody.firstChild);

    newRow.querySelector('.newSendButton').addEventListener('click', function() {
        var rowNumber = this.getAttribute('data-row');
        var newDate = document.getElementById(`newDate-${rowNumber}`).value;
        var newTime = document.getElementById(`newTime-${rowNumber}`).value;
        var newPickUp = document.getElementById(`newPickUp-${rowNumber}`).value;
        var newDropOff = document.getElementById(`newDropOff-${rowNumber}`).value;
        var newPhone = document.getElementById(`newPhone-${rowNumber}`).value;
        var newFare = document.getElementById(`newFare-${rowNumber}`).value;
        var newTimeAway = document.getElementById(`newTimeAway-${rowNumber}`).value;
        var newStatus = document.getElementById(`newStatus-${rowNumber}`).value;

        // Validation
        if (!newDate || !newTime || !newPickUp || !newDropOff || !newPhone || !newFare || !newTimeAway || !newStatus) {
            alert('Please fill in all fields.');
            return;
        }

        // Perform an AJAX request to save the new data to the database
        $.ajax({
            url: '{{ route('dispatch.store') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                date: newDate,
                time: newTime,
                pick_up_address: newPickUp,
                drop_off_address: newDropOff,
                phone_no: newPhone,
                fare: newFare,
                time_away: newTimeAway,
                status: newStatus
            },
            success: function(response) {
                if (response.success) {
                     let dispatchId = response.dispatch;

                    newRow.innerHTML = `
                        <td class="tdclass">${rowCount+1}</td>
                        <td class="tdclass"  id="newDate-${rowCount + 1}">${newDate}</td>
                        <td class="tdclass"  id="newTime-${rowCount + 1}">${diffCurrTime}</td>
                        <td class="tdclass"  id="newPickUp-${rowCount + 1}">${newPickUp}</td>
                        <td class="tdclass"  id="newDropOff-${rowCount + 1}">${newDropOff}</td>
                        <td class="tdclass"  id="newPhone-${rowCount + 1}">${newPhone}</td>
                        <td class="tdclass"  id="newFare-${rowCount + 1}">${newFare}</td>
                        
                        <td class="tdclass">
                            <button id="edit-button-${rowCount + 1}" class="btn btn-warning" onclick="handleAction(${dispatchId}, 'edit', ${rowCount + 1})">Edit</button>
                            <button id="update-button-${rowCount + 1}" class="btn btn-success" style="display: none;" onclick="handleAction(${dispatchId}, 'update', ${rowCount + 1})">Update</button>
                            <button id="cancel-button-${rowCount + 1}" class="btn btn-danger" style="display: none;" onclick="handleAction(${dispatchId}, 'cancel', ${rowCount + 1})">Cancel</button>
                        </td>
                        <td class="tdclass"  id="newTimeAway-${rowCount + 1}">${newTimeAway}</td>
                        <td class="tdclass"  id="newStatus-${rowCount + 1}">${newStatus}</td>
                    `;
                } else {
                    alert('Failed to save the data.');
                }
            },
            error: function(xhr) {
                alert('An error occurred while saving the data.');
            }
        });
    });
});

$(document).ready(function(){

    function cancelDispatch(id) {
        $.ajax({
            url: '/dispatch/' + id + '/cancel',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    var row = document.querySelector(`#dispatchTableBody tr td:first-child:contains('${id}')`).parentNode;
                    row.remove();
                } else {
                    alert('Failed to cancel the dispatch.');
                }
            },
            error: function(xhr) {
                alert('An error occurred while canceling the dispatch.');
            }
        });
    }

    
});

</script>
@endsection



@section('content')
<div class="container" style="padding:0px">
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
{{-- <div class="row" style="max-width: 99%; margin: 1px auto;">
       
    </div> --}}
    <div class="row" id="full-screen" style="max-width: 99%; margin: 1px auto; font-size: 12px; background-color:white;     align-content: start;">
        <div class="col-md-12 col-12">
            <div class="Company" style="text-align: center;">Dispatch</div>
        </div>
        <div class="col-md-6 col-6" style="text-align: left;">
            <button id="fullScreenButton" class="btn btn-outline-dark" onclick="toggleFullScreen()">Full Screen</button>
            <button id="exitFullScreenButton" class="btn btn-outline-dark" style="display: none;" onclick="toggleFullScreen()">Exit Full Screen</button>
        </div>
        <div class="col-md-6 col-6" style="text-align: right;">
            <button id="insertButton" class="btn btn-outline-primary "><span>+</span>Insert</button>
        </div>

        <div class="col-12" style="margin: 1px auto;">
            <table id="customDataTable" class="table datatable table-bordered table-hover table-responsive nowrap" style="width:100%; font-size: small;">
                <thead>
                    <tr>
                        <th class="thclass" scope="col">#</th>
                        <th class="thclass" scope="col">Date</th>
                        <th class="thclass" scope="col">Time</th>
                        <th class="thclass" scope="col">Pick up address</th>
                        <th class="thclass" scope="col">Drop off Address</th>
                        <th class="thclass" scope="col">Phone Number</th>
                        <th class="thclass" scope="col">Fare</th>
                        <th class="thclass" scope="col">Actions</th>
                        <th class="thclass" scope="col">Time away</th>
                        <th class="thclass" scope="col">Job status</th>
                    </tr>
                </thead>
                <tbody id="dispatchTableBody">
                    @php
                        $dispatchCount = count($dispatchs);
                    @endphp
                    @if($dispatchs!=null)
                    @foreach ($dispatchs as $dispatch)
                        <tr id="dispatch-row-{{  $dispatchCount }}">
                            <td class="tdclass">{{ $dispatchCount }}</td>
                            @php
                                $currentDateTime = $dispatch['created_at'];
                                $formattedCurrentDate = $currentDateTime->format("Y-m-d");
                                $formattedCurrentTime = $currentDateTime->format("h:i:s A");
                            @endphp
                            <td class="tdclass" id="newDate-{{ $dispatchCount }}">{{ $formattedCurrentDate }}</td>
                            <td class="tdclass" id="newTime-{{ $dispatchCount }}">{{ $formattedCurrentTime }}</td>
                            <td class="tdclass" id="newPickUp-{{ $dispatchCount }}">{{ $dispatch['pick_up_address'] }}</td>
                            <td class="tdclass" id="newDropOff-{{ $dispatchCount }}">{{ $dispatch['drop_off_address'] }}</td>
                            <td class="tdclass" id="newPhone-{{ $dispatchCount }}">{{ $dispatch['phone_no'] }}</td>
                            <td class="tdclass" id="newFare-{{ $dispatchCount }}">{{ $dispatch['fare'] }}</td>
                            <td class="tdclass">
                                <button id="edit-button-{{  $dispatchCount }}" class="btn btn-warning" onclick="handleAction({{ $dispatch['id'] }}, 'edit', {{$dispatchCount}})">Edit</button>
                                <button id="update-button-{{  $dispatchCount }}" class="btn btn-success" style="display: none;" onclick="handleAction({{ $dispatch['id'] }}, 'update', {{$dispatchCount}})">Update</button>
                                <button id="cancel-button-{{  $dispatchCount }}" class="btn btn-danger" style="display: none;" onclick="handleAction({{ $dispatch['id'] }}, 'cancel',  {{$dispatchCount}})">Cancel</button>
                            </td>
                            <td class="tdclass" id="newTimeAway-{{ $dispatchCount }}">{{ $dispatch['time_away'] }}</td>
                            <td class="tdclass" id="newStatus-{{ $dispatchCount }}">{{ $dispatch['status'] }}</td>
                        </tr>
                        @php
                            $dispatchCount--;
                        @endphp
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection




