@extends('layouts.app')

@section('content')

  <div class="site-wrapper">
    <div class="admin_form">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="admin_box">

                      <div class="tab_title">
                        <h3>Users</h3>
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
                        <form method="POST" action="{{ route('user.store') }}">
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
                                        <select id="company" class="form-select " name="company_id" required>
                                            <option value disabled selected>Select Company</option>
                                            @if($companies!=null)
                                            @foreach($companies as $company)
                                            <option value="{{$company['id']}}">{{$company['company']}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @error('company_id')
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                      @enderror
                                    </div>
                                  </div>
                                  
                                  
                                  <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Role') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="user_select_form">
                                        <select id="roles" class="form-select " name="roles[]" required>
                                            <option value disabled selected>Select Role</option>
                                            <option value="merchandiser">Merchandiser</option>
                                            <option value="manager">Manager</option>
                                            <option value="Merchandiser & Manager">Merchandiser & Manager</option>
                                        </select>
                                      @error('roles')
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
                                      <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" autocomplete="email" required>
                                      @error('email')
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                      @enderror
                                    </div>
                                </div>
                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="user_input_form">
                                        <input type="text"  class="form-control" id="full_name" name="name" required autocomplete="name"   >
                                      @error('name')
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                      @enderror
                                    </div>
                                </div>
                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Access Privileges') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="user_select_form">
                                        <select id="access_privilege" class="form-select "  name="access_privilege" required>
                                            <option value disabled selected>Select Access Privileges</option>
                                            <option value="Active">Active</option>
                                            <option value="Deactivated">Deactivated</option>
                                        </select>
                                        @error('access_privilege')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
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
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required  placeholder="Password">
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