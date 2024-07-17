@extends('layouts.app')

@section('content')

  <div class="site-wrapper">
    <div class="admin_form">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="admin_box">

                      <div class="tab_title">
                        <h3>Product</h3>
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
                        <form method="POST" action="{{ route('product.store') }}">
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
                                                    <option value="{{$company['id']}}" data-stores="{{ json_encode($company->stores) }}" data-categories="{{ json_encode($company->categories) }}">{{$company['company']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Store') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="user_select_form">
                                        <select id="store" name="store_id" class="form-select" required>
                                            <option value="" disabled selected>Select Store</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Category') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="user_select_form">
                                        <select id="category" name="category" class="form-select" required>
                                            <option value="" disabled selected>Select Category</option>
                                        </select>
                                        @error('category')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        $('#company').change(function() {
                                            var selectedCompany = $(this).find(':selected');
                                            var stores = JSON.parse(selectedCompany.attr('data-stores'));
                                            var categories = JSON.parse(selectedCompany.attr('data-categories'));
                                
                                            // Update the store dropdown
                                            populateDropdown('#store', stores, 'Select Store');
                                
                                            // Update the category dropdown
                                            populateDropdown('#category', categories, 'Select Category');
                                        });
                                
                                        function populateDropdown(dropdownId, options, defaultOptionText) {
                                            var dropdown = $(dropdownId);
                                            dropdown.empty();
                                            dropdown.append('<option value="" disabled selected>' + defaultOptionText + '</option>');
                                
                                            if (options && options.length > 0) {
                                                $.each(options, function(key, value) {
                                                    if(dropdownId== '#store')
                                                    {
                                                        dropdown.append('<option value="'+value.id+'">'+value.name_of_store+'</option>');
                                                    }
                                                    else
                                                    {
                                                        dropdown.append('<option value="'+value.id+'">'+value.category+'</option>');
                                                        
                                                    }
                                                });
                                            } else {
                                                dropdown.append('<option value="" disabled>No options available</option>');
                                            }
                                        }
                                    });
                                </script>
                                
                                
                                  <div class="user_form_content">
                                    <div class="label">
                                        <label>{{ __('Product Name') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="user_input_form">
                                        <input type="text" required  class="form-control" id="product_name" name="product_name" required autocomplete="product_name" autofocus  placeholder="">
                                        @error('product_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                    <div class="user_form_content">
                                        <div class="label">
                                            <label>{{ __('Product Number / SKU') }} <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="user_input_form">
                                            <input type="text" required class="form-control" id="product_number_sku" name="product_number_sku" required autocomplete="product_number_sku" autofocus  placeholder="">
                                            @error('product_number_sku')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="user_form_content">
                                        <div class="label">
                                            <label>{{ __('Competitor Product Name') }} <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="user_input_form row" id="repeater-container">
                                            <div class="col-6 p-1">
                                                <div class="w-100">
                                                    <div class="user_btn myborder" style="border: 1px solid #37A849 !important">
                                                        <input type="text" required  class="border-none user_input_form_90 height-30px" name="competitor_product_name[]" required autocomplete="competitor_product_name" autofocus placeholder="">
                                                    </div>
                                                </div>
                                                <div  class="text-danger cross-btn clickable-element p-1" onclick="removeRepeaterItem(this)">x</div>

                                            </div>
                                            <!-- This is the container for the repeater items -->
                                        </div>
                                        
                                        <div class=" user_btn myborder label">
                                            <div class=" user_btn_style submit clickable-element" onclick="addRepeaterItem()">Add</div>
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

<script>
    function addRepeaterItem() {
        const repeaterContainer = document.getElementById('repeater-container');
        const newItem = document.createElement('div');
        newItem.classList.add("col-6");
        newItem.classList.add("p-1");
        newItem.innerHTML = `
        <div class="w-100">
            <div class="user_btn myborder" style="border: 1px solid #37A849 !important">
                <input type="text" required class="border-none user_input_form_90 height-30px" name="competitor_product_name[]" required autocomplete="competitor_product_name" autofocus placeholder="">
            </div>
        </div>
        <div  class="text-danger cross-btn clickable-element p-1" onclick="removeRepeaterItem(this)">x</div>

    `;
    repeaterContainer.appendChild(newItem);
}

function removeRepeaterItem(button) {
    button.parentElement.remove();
}

</script>

@endsection