@extends('layouts.app')

@section('content')
  <div class="site-wrapper">
    <div class="admin_form">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="admin_box">

                      <div class="tab_title">
                        <h3>Dispatch</h3>
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
                        <form method="POST" action="{{ route('dispatch.store') }}">
                            @csrf

                            <div class="">
                              <div class="user_form_box">
                                  <div class="form_title">
                                      <h4>Dispatch Create Page</h4>
                                  </div>
                                  <div class="user_form_content">
                                      <div class="label">
                                          <label>{{ __('Pick up Address') }} <span class="text-danger">*</span></label>
                                      </div>
                                      <div class="user_input_form">
                                        <input type="text" class="form-control" id="pick_up_address" name="pick_up_address" required autocomplete="pick_up_address" autofocus  placeholder="pick Up Address">
                                      </div>
                                  </div>

                                  <div class="user_form_content">
                                      <div class="label">
                                          <label>{{ __('Drop Off Address') }} <span class="text-danger">*</span></label>
                                      </div>
                                      <div class="user_input_form">
                                        <input type="text" class="form-control" id="drop_off_address" name="drop_off_address" required autocomplete="drop_off_address" autofocus  placeholder="Drop Off Address">
                                      </div>
                                  </div>



                                  <div class="user_form_content">
                                      <div class="label">
                                          <label>{{ __('Telephone') }} <span class="text-danger">*</span></label>
                                      </div>
                                      <div class="user_input_form">
                                        <input type="number" class="form-control"  pattern="\d{11}" id="phone_no" name="phone_no" required autocomplete="phone_no" autofocus  placeholder="Telephone">
                                      </div>
                                  </div>

                                  <div class="user_form_content">
                                      <div class="label">
                                          <label>{{ __('Fare') }} <span class="text-danger">*</span></label>
                                      </div>
                                      <div class="user_input_form">
                                        <input type="number" class="form-control" id="fare" name="fare" required autocomplete="fare" autofocus  placeholder="Fare">
                                      </div>
                                  </div>

                                  <!-- <div class="user_form_content">
                                      <div class="label">
                                          <label>{{ __('Send Button') }} <span class="text-danger">*</span></label>
                                      </div>
                                      <div class="user_input_form">
                                        <input type="text" class="form-control" id="send_button" name="send_button" required autocomplete="send_button" autofocus  placeholder="Send Button">
                                      </div>
                                  </div> -->

                                  <div class="user_form_content">
                                      <div class="label">
                                          <label>{{ __('Time Away') }} <span class="text-danger">*</span></label>
                                      </div>
                                      <div class="user_input_form">
                                        <input type="text" class="form-control" id="time_away" name="time_away" required autocomplete="time_away" autofocus  placeholder="Time Away">
                                      </div>
                                  </div>

                                  <!-- <div class="user_form_content">
                                      <div class="label">
                                          <label>{{ __('Status') }} <span class="text-danger">*</span></label>
                                      </div>
                                      <div class="user_input_form">
                                        <input type="text" class="form-control" id="status" name="status" required autocomplete="status" autofocus  placeholder="Status">
                                      </div>
                                  </div> -->




                                  <div class="user_btn_list">
                                      {{-- <div class="user_btn text-secondary" >
                                          <div class="user_btn_style"> <img src="{{asset('assets/images/save.png')}}"> Save Changes</div>
                                      </div> --}}
                                      <div class="user_btn myborder">
                                        <button type="submit" class=" user_btn_style submit ">
                                         <img src="{{asset('assets/images/next.png')}}" alt="->"> Submit
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
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
