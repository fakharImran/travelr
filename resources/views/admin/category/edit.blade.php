@extends('layouts.app')

@section('content')

  <div class="site-wrapper">
    <div class="admin_form">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="admin_box">

                      <div class="tab_title">
                        <h3>Category</h3>
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
                      <form method="POST" action="{{route("category.update", $id)}}">
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
                                            <option value="" disabled selected>Select Company</option>
                                            @if($companies != null)
                                                @foreach($companies as $company)
                                                <option {{ $company['id'] == $category['company_id'] ? 'selected' : '' }} value="{{$company['id']}}">{{$company['company']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                                                
                                  <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Category') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="user_select_form">
                                        <input type="text" required  class="form-control" value="{{$category->category}}" id="category" name="category" required autocomplete="category" autofocus  placeholder="">
                                        @error('category')
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