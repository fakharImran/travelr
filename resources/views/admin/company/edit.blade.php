@extends('layouts.app')

@section('content')

  <div class="site-wrapper">
    <div class="admin_form">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="admin_box">

                      <div class="tab_title">
                        <h3>Company</h3>
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
                        <form method="POST" action="{{route("company.update", $id)}}">
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
                                      <div class="user_input_form">
                                        <input type="text" class="form-control" id="company_name" name="company_name" value="{{$company['company']}}" required autocomplete="company_name" autofocus  placeholder="Company Name">
                                      </div>
                                  </div>

                                  <div class="user_form_content">
                                      <div class="label">
                                          <label>{{ __('Company Code') }} <span class="text-danger">*</span></label>
                                      </div>
                                      <div class="user_input_form">
                                        <input type="text" min="1000" max="9999" pattern="\d{4}" maxlength="4" value="{{$company['code']}}"  class="form-control" id="company_code" name="company_code" required autocomplete="company_code" autofocus  placeholder="4-digit code only (e.g., 1234)">
                                        @error('company_code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                      </div>
                                  </div>
                                  <div class="user_btn_list">
                                    <div class="user_btn myborder">
                                        <button type="submit" class=" user_btn_style submit ">
                                         <img src="{{asset('assets/images/save.png')}}" alt="->"> Save Changes
                                        </button>
                                    </div>
                                    {{-- <div class="user_btn  text-secondary">
                                        <div  class="user_btn_style">
                                         <img src="{{asset('assets/images/next.png')}}" alt="->"> Submit
                                        </div>
                                    </div> --}}
                                    {{-- <div class="user_btn mybordery" >
                                        <a href="{{ route('company-delete',   $company['id']) }}" class="user_btn_style"  style="color: black; border:none;" >
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



@endsection