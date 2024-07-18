@extends('layouts.app')

@section("top_links")
<link rel="stylesheet" href ="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
@endsection

@section("bottom_links")
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#customDataTable').DataTable({
        "responsive": true,
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "columnDefs": [
            { "orderable": false, "targets": -1 } // Disable ordering on the last column (Send Button)
        ]
    });

    // Add filters for each column
    $('#customDataTable thead tr').clone(true).appendTo( '#customDataTable thead' );
    $('#customDataTable thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        if (title !== '#' && title !== 'Send Button') { // Exclude columns that don't need filters
            $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
            $( 'input', this ).on( 'keyup change clear', function () {
                if ( table.column(i).search() !== this.value ) {
                    table
                        .column(i)
                        .search( this.value )
                        .draw();
                }
            } );
        } else {
            $(this).html('');
        }
    });

    var table = $('#customDataTable').DataTable();
});
</script>
@endsection

@section('content')
<div class="container" style="padding:0px">
    <div class="row" style="max-width: 99%; margin: 1px auto;">
        <div class="col-md-12 col-12">
            <div class="Company" style="text-align: center;">Dispatch History</div>
        </div>
    </div>
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

    <div class="row" style="max-width: 99%; margin: 1px auto; font-size: 12px;">
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
                        <th class="thclass" scope="col">Send Button</th>
                        <th class="thclass" scope="col">Time away</th>
                        <th class="thclass" scope="col">Job status</th>
                    </tr>
                </thead>
                @php
                    $dispatchCount = count($dispatchHistory);
                @endphp
                <tbody>
                    @if($dispatchHistory != null)
                        @foreach ($dispatchHistory as $dispatch)
                            <tr>
                                <td class="tdclass">{{ $dispatchCount }}</td>
                                @php
                                    $createdTime = new DateTime($dispatch['created_at']);
                                    $formattedCreatedDate = $createdTime->format("Y-m-d");
                                    $formattedCreatedTime = $createdTime->format("h:i:s A");
                                @endphp
                                <td class="tdclass">{{ $formattedCreatedDate }}</td>
                                <td class="tdclass">{{ $formattedCreatedTime }}</td>
                                <td class="tdclass">{{ $dispatch['pick_up_address'] }}</td>
                                <td class="tdclass">{{ $dispatch['drop_off_address'] }}</td>
                                <td class="tdclass">{{ $dispatch['phone_no'] }}</td>
                                <td class="tdclass">{{ $dispatch['fare'] }}</td>
                                <td class="tdclass">{{ $dispatch['send_button'] }}</td>
                                <td class="tdclass">{{ $dispatch['time_away'] }}</td>
                                <td class="status" id="status-{{ $dispatch['id'] }}" style="color: {{ $dispatch['status'] == 'completed' ? 'green' : 'red' }};">
                                    {{ $dispatch['status'] }}
                                </td>
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
