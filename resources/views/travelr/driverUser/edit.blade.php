@extends('layouts.app')

@section('content')

  <div class="site-wrapper">
    <div class="admin_form">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="admin_box">

                      <div class="tab_title">
                        <h3>Driver</h3>
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
                      <form method="POST" action="{{route("driver.update", $id)}}">
                            @method('PUT')
                            @csrf

                            <div class="">
                              <div class="user_form_box">
                                  <div class="form_title">
                                      <h4>Driver Edit Page</h4>
                                  </div>


                                  @php
                                     $name= explode(' ', $driverUserData->user['name']);
                                 @endphp

                                  <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('First Name') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="user_input_form">
                                        <input type="text"  class="form-control" id="first_name" value="{{$name[0]}}" name="first_name" required autocomplete="first_name"   >
                                      @error('first_name')
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                      @enderror
                                    </div>
                                </div>

                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Last Name') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="user_input_form">
                                        <input type="text"  class="form-control" id="last_name"  value="{{$name[1]}}" name="last_name" required autocomplete="last_name"   >
                                      @error('last_name')
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                      @enderror
                                    </div>
                                </div>

                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="user_input_form">
                                      <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{$driverUserData->user['email']}}" name="email" autocomplete="email" required>
                                      @error('email')
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                      @enderror
                                    </div>
                                </div>

                                <div class="user_form_content">
                                      <div class="label">
                                          <label>{{ __('Telephone') }} <span class="text-danger">*</span></label>
                                      </div>
                                      <div class="user_input_form">
                                        <input type="number" class="form-control"  pattern="\d{11}" id="phone_no" name="phone_no" value="{{$driverUserData->user['phone_no']}}" required autocomplete="phone_no" autofocus  placeholder="Telephone">
                                      </div>
                                  </div>


                                <br>
                                <br>
                                <div class="form_title">
                                    <h4>Password</h4>
                                </div>
                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Password') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="user_input_form">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" value="{{$driverUserData->user['passhowd']}}" name="password" required  placeholder="Password">
                                        <span class="toggle-password fa fa-eye"  onclick="togglePasswordVisibility()"></span>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="user_input_form">
                                        <input id="password-confirm" type="password" class="form-control" name="confirm-password" required autocomplete="confirm-password">
                                        <span class="toggle-password fa fa-eye" onclick="toggleConfirmPasswordVisibility()"></span>
                                        @error('confirm-password')
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
                                       <img src="{{asset('assets/images/next.png')}}" alt="->"> Save Changes
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
<script>

//     $(document).ready(function() {
//     $('.select2').select2({
//         placeholder: 'Select Role',
//         minimumResultsForSearch: Infinity,
//         allowClear: true
//     });
//     // $(".select2-search, .select2-focusser").remove();
// });

function togglePasswordVisibility() {
  var passwordField = document.getElementById('password');
  if (passwordField.type === 'password') {
    passwordField.type = 'text';
  } else {
    passwordField.type = 'password';
  }
}
function toggleConfirmPasswordVisibility() {
  var passwordField = document.getElementById('password-confirm');
  if (passwordField.type === 'password') {
    passwordField.type = 'text';
  } else {
    passwordField.type = 'password';
  }
}
</script>
@endsection
