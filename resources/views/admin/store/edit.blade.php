@extends('layouts.app')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script>
    /**
     * @license
     * Copyright 2019 Google LLC. All Rights Reserved.
     * SPDX-License-Identifier: Apache-2.0
     */
    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            center: {
                lat: 40.749933,
                lng: -73.98633
            },
            zoom: 13,
            mapTypeControl: false,
        });
        const inputs = document.getElementsByClassName("pac-input");
console.log(inputs);
        const options = {
            fields: ["formatted_address", "geometry", "name"],
            strictBounds: false,
            types: ["establishment"],
        };

        for (let i = 0; i < inputs.length; i++) {
            const autocomplete = new google.maps.places.Autocomplete(inputs[i], options);
        }
        
    }
    window.initMap = initMap;
</script>
@section('content')

  <div class="site-wrapper">
    <div class="admin_form">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="admin_box">

                      <div class="tab_title">
                        <h3>Store</h3>
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

                      <form method="POST" action="{{route("store.update", $id)}}">
                        @method('PUT')
                        @csrf
                       
                            <div class="">
                              <div class="user_form_box">
                                  <div class="form_title">
                                      <h4>General</h4>
                                  </div>
                                  <div class="user_form_content">
                                      <div class="label">
                                          <label>{{ __('Company') }} <span class="text-danger">*</span></label>
                                      </div>
                                      <div class="user_select_form">
                                        <select id="company" name="company_id" class="form-select" required>
                                            <option value disabled>Select Company</option>
                                            @if($companies!=null)
                                            @foreach($companies as $comp)
                                            <option {{ $comp['id'] == $store['company_id'] ? 'selected' : '' }} value="{{ $comp['id'] }}">{{ $comp['company'] }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @error('company')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                  </div>
                                  <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Name of Store') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="user_input_form">
                                        <input type="text" required value="{{$store['name_of_store']}}"  class="form-control" id="name_of_store" name="name_of_store" required autocomplete="name_of_store" autofocus  placeholder="">
                                        @error('name_of_store')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                   {{-- {{ dd(count($store->locations))}} --}}
                                    @php
                                        $selectedParish = json_decode($store['parish'])
                                    @endphp
                                    <div class="card">
                                        <div class="card-body p-4"  id="repeater-container">
                                            @for($i = 0; $i< count($store->locations); $i++)
                                            <div>
                                                <div class="user_form_content">
                                                    <div class="label">
                                                        <label>{{ __('Parish') }} <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="user_select_form">
                                                        <select id="parish" name="parish[]" class="form-select" required >
                                                            <option value="" disabled selected>Select Parish</option>
                                                            <option {{($selectedParish[$i] == 'Clarendon')?'selected':''}} value="Clarendon">Clarendon</option>
                                                            <option {{($selectedParish[$i] == 'Hanover')?'selected':''}} value="Hanover">Hanover</option>
                                                            <option {{($selectedParish[$i] == 'Kingston')?'selected':''}} value="Kingston">Kingston</option>
                                                            <option {{($selectedParish[$i] == 'Manchester')?'selected':''}} value="Manchester">Manchester</option>
                                                            <option {{($selectedParish[$i] == 'Portland')?'selected':''}} value="Portland">Portland</option>
                                                            <option {{($selectedParish[$i] == 'St. Andrew')?'selected':''}} value="St. Andrew">St. Andrew</option>
                                                            <option {{($selectedParish[$i] == 'St. Ann')?'selected':''}} value="St. Ann">St. Ann</option>
                                                            <option {{($selectedParish[$i] == 'St. Catherine')?'selected':''}} value="St. Catherine">St. Catherine</option>
                                                            <option {{($selectedParish[$i] == 'St. Elizabeth')?'selected':''}} value="St. Elizabeth">St. Elizabeth</option>
                                                            <option {{($selectedParish[$i] == 'St. James')?'selected':''}} value="St. James">St. James</option>
                                                            <option {{($selectedParish[$i] == 'St. Mary')?'selected':''}} value="St. Mary">St. Mary</option>
                                                            <option {{($selectedParish[$i] == 'St. Thomas')?'selected':''}} value="St. Thomas">St. Thomas</option>
                                                            <option {{($selectedParish[$i] == 'Trelawny')?'selected':''}} value="Trelawny">Trelawny</option>
                                                            <option {{($selectedParish[$i] == 'Westmoreland')?'selected':''}} value="Westmoreland">Westmoreland</option>
                                                        </select>
                                                        @error('parish')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="user_form_content">
                                                    <div class="label">
                                                        <label>{{ __('Search Location') }} <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="user_input_form ">
                                                        <div class="col-12">
                                                            <input id="pac-input" class="form-control pac-input" type="text"  name="locations[{{$store->locations[$i]->id}}]" value="{{ $store->locations[$i]->location }}" required autocomplete="location" autofocus placeholder=""/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div  class="  clickable-element p-1 btn btn-danger" onclick="removeRepeaterItem(this)">Delete</div>
                                                <hr>
                                            </div>
                                                
                                            @endfor 
                                        </div>    
                                    </div>    
                                        <div id="map" style="display: none"></div>

                                <div class="mb-5 p-3" >
                                    <div class=" user_btn myborder label float-end">
                                        <div class=" user_btn_style submit clickable-element" onclick="addRepeaterItem()">Add New</div>
                                    </div>
                                </div>
                                
                                    
                                    
                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Channel') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="user_select_form">
                                      <select id="channel" class="form-select" required name="channel">
                                        <option {{($store['channel']=='')? "selected":""}} value disabled>Select Channel</option>
                                        <option {{($store['channel']=='Bar')? "selected":""}} value="Bar">Bar</option>
                                        <option {{($store['channel']=='Pharmacy')? "selected":""}} value="Pharmacy">Pharmacy</option>
                                        <option {{($store['channel']=='Supermarket')? "selected":""}} value="Supermarket">Supermarket</option>
                                        <option {{($store['channel']=='Wholesale')? "selected":""}} value="Wholesale">Wholesale</option>
                                      </select>
                                    </div>
                                </div>
                                <div class="user_btn_list">
                                    <div class="user_btn myborder">
                                        <button type="submit" class=" user_btn_style submit  ">
                                         <img src="{{asset('assets/images/save.png')}}" alt="->"> Save Changes
                                        </button>
                                    </div>
                                    {{-- <div class="user_btn  text-secondary">
                                        <div  class="user_btn_style">
                                         <img src="{{asset('assets/images/next.png')}}" alt="->"> Submit
                                        </div>
                                    </div> --}}
                                    {{-- <div class="user_btn  myborder" >
                                        <a href="{{ route('store-delete',   $id) }}" class="user_btn_style"  style="color: black; border:none;" >
                                        <img src="{{asset('assets/images/del_user.png')}}"> Delete User
                                        
                                        </a>
                                    </div> --}}
                                    <div class="user_btn myborder" onclick="window.history.go(-1); return false;" >
                                        <button  class="user_btn_style submit" > <img src="{{asset('assets/images/close.png')}}"> Close</button>
                                    </div>
                                  </div>
                              </div>
                          </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function addRepeaterItem() {
    const repeaterContainer = document.getElementById('repeater-container');
    const newItem = document.createElement('div');
    // newItem.classList.add("p-1");
    newItem.innerHTML = `
        <div class="user_form_content">
            <div class="label">
                <label>{{ __('Parish') }} <span class="text-danger">*</span></label>
            </div>
            <div class="user_select_form">
                <select id="parish" name="parish[]" class="form-select" required >
                    <option value="" disabled selected>Select Parish</option>
                    <option value="Clarendon">Clarendon</option>
                    <option value="Hanover">Hanover</option>
                    <option value="Kingston">Kingston</option>
                    <option value="Manchester">Manchester</option>
                    <option value="Portland">Portland</option>
                    <option value="St. Andrew">St. Andrew</option>
                    <option value="St. Ann">St. Ann</option>
                    <option value="St. Catherine">St. Catherine</option>
                    <option value="St. Elizabeth">St. Elizabeth</option>
                    <option value="St. James">St. James</option>
                    <option value="St. Mary">St. Mary</option>
                    <option value="St. Thomas">St. Thomas</option>
                    <option value="Trelawny">Trelawny</option>
                    <option value="Westmoreland">Westmoreland</option>
                </select>
                @error('parish')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="user_form_content">
            <div class="label">
                <label>{{ __('Search Location') }} <span class="text-danger">*</span></label>
            </div>
            <div class="user_input_form ">
                <div class="col-12">
                    <input id="pac-input"  class="form-control pac-input" type="text"  name="locations[]" required autocomplete="location" autofocus placeholder=""/>
                </div>
            </div>
        </div>
        <div  class="  clickable-element p-1 btn btn-danger" onclick="removeRepeaterItem(this)">Delete</div>
        <hr>
        
    ` ;
    // user_input_form
    repeaterContainer.appendChild(newItem);
    initMap(); 
}

function removeRepeaterItem(button) {
    button.parentElement.remove();
}
</script>

<script>
    // $(document).ready(function() {
    //     $('#parish').select2();
    //     var selectedParish = {!! json_encode($selectedParish) !!};
    //     $('#parish').val(selectedParish);
    //     $('#parish').trigger('change');
    // });
</script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAob5VEgwOWzV91Q7y4ZncX5XrM33Fa-eo&callback=initMap&libraries=places&v=weekly"
    defer>
</script>


@endsection