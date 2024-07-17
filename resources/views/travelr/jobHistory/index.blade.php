@extends('layouts.app')

@section("top_links")
<link rel="stylesheet" href ="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> --}}
@endsection

@section("bottom_links")
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<script>


@section('content')
<div class="container" style="padding:0px">

    <div class="row" style="   max-width: 99%; margin: 1px auto;">
        <div class="col-md-12 col-12">
            <div class="Company" style="text-align: center;" >Dispatch History
            </div>

        </div>
        {{-- <div class="col-md-1 col-3"  style="margin: 1px auto;">
            <div class="add_btn">
                <a href="{{ route('company.create') }}"> <span>+</span>New</a>
            </div>
        </div> --}}
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

    <div class="row" style="    max-width: 99%; margin: 1px auto; font-size: 12px;">


        <div class="col-12" style="margin: 1px auto; ">
            <table id="customDataTable" class="table  datatable table-bordered table-hover table-responsive nowrap" style="width:100%; font-size: small;">
                <thead>
                    <tr>
                    <th class="thclass" scope="col">#</th>
                    <th class="thclass"  scope="col">Date</th>
                    <th class="thclass"  scope="col">Time</th>
                    <th class="thclass"  scope="col">Pick up address</th>
                    <th class="thclass"  scope="col">Drop off Address</th>
                    <th class="thclass"  scope="col">Phone Number</th>
                    <th class="thclass"  scope="col">Fare</th>
                    <th class="thclass"  scope="col">Send Button</th>
                    <th class="thclass"  scope="col">Time away</th>
                    <th class="thclass"  scope="col">Job status</th>
                    </tr>

                </thead>
                @php
                    $i=1;
                @endphp
                <tbody>
                    @if($dispatchHistory!=null)
                    @foreach ($dispatchHistory as $dispatch)
                        <tr>
                            <td class="tdclass">{{ $i}}</td>
                            @php
                                $createdTime = new DateTime($dispatch['created_at']);

                                // Format the DateTime object in 12-hour format
                                $formattedCreatedDate = $createdTime->format("Y-m-d");
                                $formattedCreatedTime = $createdTime->format("h:i:s A");
                            @endphp
                            <td class="tdclass">{{ $formattedCreatedDate }}</td>
                            <td class="tdclass">{{ $formattedCreatedTime }}</td>
                            <td class="tdclass">{{ $dispatch['pick_up_address'] }}</td>
                            <td class="tdclass">{{ $dispatch['drop_off_address'] }}</td>
                            <td class="tdclass">{{ $dispatch['phone_no'] }}</td>
                            <td class="tdclass">{{ $dispatch['fare'] }}</td>
                            <td class="tdclass"> {{$dispatch['send_button']}}</td>

                            <td class="tdclass">{{ $dispatch['time_away'] }}</td>
                            <td class="status" id="status-{{ $dispatch['id'] }}" style="color: {{ $dispatch['status'] == 'completed' ? 'green' : 'red' }};">
                                {{ $dispatch['status'] }}
                            </td>

                        </tr>
                        @php
                            $i++;
                        @endphp
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection