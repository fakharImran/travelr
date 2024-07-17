
@extends('manager.layout.app')

@section("top_links")

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


@endsection

@section("bottom_links")

<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    
<script>

</script>
@endsection

@section('content')

    <div class="container">
        <form id="NotificationForm" class="needs-validation" method ="POST" class="row gy-1 pt-75" action="{{route('web_notification.store')}}" novalidate  enctype="multipart/form-data">
            @csrf
            <div class="">
                <div class="user_form_box">
                    <div class="form_title">
                        <h4>General</h4>
                    </div>
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="user_form_content">
                        <div class="label">
                            <label>{{ __('Title:') }} <span class="text-danger">*</span></label>
                        </div>
                        <div class="user_input_form">
                            <input type="text"  class="form-control" id="title" name="title" required autocomplete="name"   >
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                    </div>
                    <div class="user_form_content">
                        <div class="label">
                            <label>{{ __('Message:') }} <span class="text-danger">*</span></label>
                        </div>
                        <div class="user_input_form">
                            <input type="text"  class="form-control" id="essage" name="message" required autocomplete="message"   >
                        @error('message')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                    </div>

                    
                    <div class="user_form_content">
                        <div class="label">
                            <label>{{ __('Select Merchandiser:') }}</label>
                        </div>
                        <div class="user_select_form">
                            <select name="user_id[]" class=" form-select select2" multiple  id="merchandiser-search">
                                {{-- <option value="" selected>--Select-- </option> --}}
                                @foreach($userArr as $merchandiser)
                                        <option value="{{$merchandiser->id}}">{{$merchandiser->name}}</option>
                                @endforeach
                            </select>   
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="chkall">
                                <label class="form-check-label" for="chkall">
                                    Select All Merchandisers
                                </label>
                            </div>
                            @error('user_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                    </div>
                    
                    <div class="form_title">
                        <h4>Attachment (Optional)</h4>
                    </div>
                    
                    <div class="user_form_content">
                        <div class="label">
                            <label>{{ __('Name of Store:') }}</label>
                        </div>
                        <div class="user_select_form">
                            <select name="store_id" class="form-select" id="store-search">
                                <option value="" selected>--Select--</option>
                                @if($stores!=null)
                                    @foreach ($stores as $store)
                                        <option value="{{$store['id']}}">{{$store['name_of_store']}}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('store_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                    </div>
                    @php
                        $locationArr=array();
                        $storesArr=array();
                    @endphp
                @if($stores!=null)
                @foreach ($stores as $store)
                        @php
                            $tempLocation=array();
                        @endphp
        
                        @foreach($store->locations->unique('location')->sort() as $location)
                            @php
                                array_push($locationArr, ['store_location_id'=>$location['id'],'location'=>$location['location'],]); 
                                array_push($tempLocation, $location['location']) ;                             
                            @endphp    
                        @endforeach
                        @php
                            $uniqueLocation = array_unique($tempLocation);
                            sort($uniqueLocation);
                            array_push($storesArr, [$store->name_of_store,$uniqueLocation ]);
        
                        @endphp
                    @endforeach
                @endif
                @php
                @endphp
                    <div class="user_form_content">
                        <div class="label">
                            <label>{{ __('Location:') }}</label>
                        </div>
                        <div class="user_select_form">
                            <select id="store_location_id" class="form-select " name="store_location_id" >
                                <option value selected>--Select--</option>
                                @if($locationArr!=null)
                                @foreach ($locationArr as $location)
                                    <option value="{{$location['store_location_id']}}">{{$location['location']}}</option>
                                @endforeach
                                @endif
                            </select>
                            @error('store_location_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                    </div>
                    <div class="user_form_content">
                        <div class="label">
                            <label>{{ __('Attachment') }}</label>
                        </div>
                        <div class="user_input_form">
                            <input type="file"  class="form-control" id="full_name" name="attachment" required autocomplete="name"   >
                        @error('Attachment')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                    </div>
                    <div class="user_btn_list">
                        {{-- <div class="user_btn text-secondary" >
                            <div class="user_btn_style"> <img src="{{asset('assets/images/save.png')}}"> Save Changes</div>
                        </div> --}}
                        <div class="user_btn myborder">
                        <button type="submit" class=" user_btn_style submit  ">
                        <img src="{{asset('assets/images/next.png')}}" alt="->"> Send
                        </button>
                        </div>

                        {{-- <div class="user_btn  text-secondary" >
                            <div class="user_btn_style"> <img src="{{asset('assets/images/del_user.png')}}"> Delete User</div>
                        </div> --}}

                        <div class="user_btn myborder" onclick="window.history.go(-1); return false;" >
                            <button  class="user_btn_style submit" > <img src="{{asset('assets/images/close.png')}}"> Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
    //  function   setLocations(data)
    //  {
    //     alert(data.value)
    //  }
    </script>
    <script>
        $(document).ready(function() {
            $('#merchandiser-search').select2();
            $("#chkall").click(function(){
                if($("#chkall").is(':checked')){
                    $("#merchandiser-search > option").prop("selected", "selected");
                    $("#merchandiser-search").trigger("change");
                } else {
                    $("#merchandiser-search > option").removeAttr("selected");
                    $("#merchandiser-search").trigger("change");
                }
            });
        });
    </script>
@endsection
