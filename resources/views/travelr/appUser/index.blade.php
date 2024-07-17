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
            <div class="Company" style="text-align: center;" >App User
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

    <div class="col-12">
            <div class="table_btn_list">

                <div class="add_btn">
                    <a href="{{ route('appuser.create') }}"> <span>+</span>Insert</a>
                </div>
            </div>
        </div>

        <div class="col-12" style="margin: 1px auto; ">
            <table id="customDataTable" class="table  datatable table-bordered table-hover table-responsive nowrap" style="width:100%; font-size: small;">
                <thead>
                    <tr>
                    <th class="thclass" scope="col">#</th>
                    <th class="thclass"  scope="col">First Name</th>
                    <th class="thclass"  scope="col">Last Name</th>
                    <th class="thclass"  scope="col">Email Address</th>
                    <th class="thclass"  scope="col">phone_no</th>
                    <th class="thclass"  scope="col">Action</th>
                    </tr>

                </thead>
                @php
                    $i=1;
                @endphp
                <tbody>
                    @if($appUsers!=null)
                    @foreach ($appUsers as $appUser)
                        <tr>
                            <td class="tdclass">{{ $i}}</td>

                            <td class="tdclass">{{ $appUser->first_name }}</td>
                            <td class="tdclass">{{ $appUser->last_name }}</td>
                            <td class="tdclass">{{ $appUser->user->email }}</td>
                            <td class="tdclass">{{ $appUser->user->phone_no }}</td>
                            <td class="tdclass">

                                <form action={{ route('appuser.destroy', $appUser->id) }} method="post">
                                    @csrf
                                    @method('DELETE')

                                    <button class="submit delete-button"><i class="fa fa-trash-o text-danger" aria-hidden="true"></i>
                                    </button>
                                    <a href="{{ route('appuser.edit',  $appUser->id) }}"><i class="fa fa-pencil-square-o text-secondary" aria-hidden="true"></i>
                                    </a>
                                </form>

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
