<div class="row d-flex align-items-center col-actions" style="max-width: 100%; margin: 1px auto;">
    <div class="col-md-2 col-2 p-1">
        <div class="form-group">
            <h2
                style="color: #2297C3;

                font-family: Inter;
                font-size: 28px;
                font-style: normal;
                font-weight: 700;
                line-height: 32px;">
                Businesss Summary</h2>
        </div>

    </div>
    <div class="col-md-2 col-2 p-1">
        <div class="form-group">
            {{-- <label for="BS-store-search" class="form-label filter store">Select Store</label> --}}
            <select name="BS-store-search" class="filter form-select select2" placeholder="Select Store"
                id="BS-store-search">
                <option value="" selected>Select Store</option>
                @if ($stores != null)
                    @foreach ($stores->unique('name_of_store')->sortBy('name_of_store') as $store)
                        <option value="{{ $store['name_of_store'] }}">{{ $store['name_of_store'] }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    {{-- for setting the filter dropdown unique and sorted value --}}
    @php
        $locationArr = [];
        $storesArr = [];

    @endphp
    @if ($stores != null)
        @foreach ($stores as $store)
            @php
                $tempLocation = [];
            @endphp

            @foreach ($store->locations->sort() as $location)
                @php
                    array_push($locationArr, $location['location']);
                    array_push($tempLocation, $location['location']);
                @endphp
            @endforeach
            @php
                $uniqueLocation = array_unique($tempLocation);
                sort($uniqueLocation);
                array_push($storesArr, [$store->name_of_store, $uniqueLocation]);

            @endphp
        @endforeach
    @endif
    @php
        // $locationArr = array_unique($locationArr);
        // dd($locationArr);
        sort($locationArr);
    @endphp
    {{-- end sorting and unique location value in filter search --}}
    <div class="col-md-2 col-2 p-1">
        <div class="form-group">
            {{-- <label for="BS-location-search" class="form-label filter location">Select Location</label> --}}
            <select name="BS-location-search" class="filter form-select select2" placeholder="Select Location"
                id="BS-location-search">
                <option value="" selected>Select Location</option>
                {{-- @foreach ($locationArr as $location)
                    <option value="{{$location}}">{{$location}}</option>
                @endforeach --}}
            </select>
        </div>

    </div>
    <div class="col-md-2 col-2 p-1">
        <div class="form-group">
            {{-- <label for="BS-category-search" class="form-label filter category">Select Category</label> --}}
            <select name="BS-category-search" placeholder="Select Category" class=" filter form-select select2"
                id="BS-category-search">
                <option value="" selected>Select Category</option>
                @foreach ($categories->unique('category')->sortBy('category') as $category)
                    <option value="{{ $category['category'] }}">{{ $category['category'] }}</option>
                @endforeach
            </select>
        </div>
    </div>
    {{-- {{dd($products)}} --}}
    <div class="col-md-2 col-2 p-1">
        <div class="form-group">
            {{-- <label for="BS-product-search" class="form-label filter product">Select product</label> --}}
            <select name="BS-product-search" placeholder="Select Product" class=" filter form-select select2"
                id="BS-product-search">
                <option value="" selected>Select Product</option>
                @foreach ($products->unique('product_name')->sortBy('product_name') as $product)
                    <option value="{{ $product['product_name'] }}">{{ $product['product_name'] }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
 {{--end business overview Search --}}

 @php
 $sumTotalStock = 0;
 $sumTotalStockCases = 0;
 $sumStockUnitCases = 0;
@endphp

{{--start business overview cards --}}

<div class="row d-flex align-items-center col-actions mt-10" style="max-width: 100%; margin-top: 50px">
 <div class="col-md-2 col-2 p-1">
     <div class="card manager-card-style">
         <div class="card-header manager-card-header">Total stock count</div>
         <div class="card-body">
             <div class="content">
                 <div class="row">
                     <div class="col-12" style="color: #37A849;">
                         <h3><b id="BS-total_stock_count">{{ $sumTotalStockCases }} </b><sub
                                 style="font-size: small;"> Cases</sub></h3>
                     </div>
                     <div class="col-12" style="color: #37A849;">
                         <h3><b id="BS-total_stock_count_cases">{{ $sumTotalStock }} </b><sub
                                 style="font-size: small;"> Units</sub></h3>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <div class="col-md-2 col-2 p-1">
     <div class="card manager-card-style">
         <div class="card-header manager-card-header">Number of Stores serviced</div>
         <div class="card-body content">
             <small id="date_range_set" class="text-secondary date_range_set">
                 @php
                     $todayDate = new DateTime();
                     // echo $todayDate->format('Y-m-d');
                 @endphp
             </small>

             @php

             @endphp
             <div class="Link0" id="BS-serviced_stores"
                 style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                 {{ $uniqueNumberOfStoreServicedCount }} / {{ count($locationArr) }}
             </div>

         </div>
     </div>
 </div>
 <div class="col-md-2 col-2 p-1">
     <div class="card manager-card-style">
         <div class="card-header manager-card-header">Number of Stores with out of stock</div>
         <div class="card-body content">
             <small id="BS-date_range_set" class="text-secondary date_range_set">
                 @php
                     $todayDate = new DateTime();
                     // echo $todayDate->format('Y-m-d');
                 @endphp
             </small>
             @php
                 $totalStores = $stores->unique('name_of_store')->count();
                 $uniqueStores = $outOfStockData->unique('store_id')->sort();
                 $uniqueStoreCount = $uniqueStores->count();
             @endphp
             <div class="Link0" id="BS-stores_out_of_stock"
                 style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                 <span style="color: #CA371B">{{ $uniqueStoreCount }}</span> / {{ count($locationArr) }}
             </div>
         </div>
     </div>
 </div>
 <div class="col-md-2 col-2 p-1">
     <div class="card manager-card-style">
         <div class="card-header manager-card-header">Number of Products out of stock in stores</div>
         <div class="card-body content">
             <small class="text-secondary date_range_set">
                 @php
                     $todayDate = new DateTime();
                     // echo $todayDate->format('Y-m-d');
                 @endphp
             </small>
             @php
                 $totalProducts = $products->unique('product_name')->count();
                 $uniqueProducts = $outOfStockData->unique('product_id')->sort();
                 $uniqueProductCount = $uniqueProducts->count();
             @endphp
             <div class="Link0" id="BS-products_out_of_stock"
                 style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                 <span style="color: #CA371B">{{ $uniqueProductCount }}</span> / {{ $totalProducts }}
             </div>
         </div>
     </div>
 </div>
 <div class="col-md-2 col-2 p-1">
     <div class="card manager-card-style">
         <div class="card-header manager-card-header">Number of Stores with Expired Products</div>
         <div class="card-body content">
             <small class="text-secondary date_range_set">
                 @php
                     $todayDate = new DateTime();
                     // echo $todayDate->format('Y-m-d');
                 @endphp
             </small>
             @php
                 $uniqueExpProduct = $productExpiryTrackerData->unique('store_id')->sort();
             @endphp
             <div class="Link0" id="BS-stores_with_exp_products"
                 style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                 <span style="color: #CA371B">{{ count($uniqueExpProduct) }} /</span>
                 {{ count($locationArr) }}
             </div>

         </div>
     </div>
 </div>
</div>

{{--end business overview cards --}}


{{--start business overview Bar chart coming from stock count by store --}}


<div class="row pt-5">
 <div class="col-12">
     <div style="width: 900px; margin-top: 50px;">
         <div class="row d-flex">
             <div class="col-4">
                 <label for="merchandiser-search" class="form-label filter merchandiser">Stock Level of
                     products in store </label>
             </div>
             <div class="col-4">
                 <select name="BS-casesorunits" onchange="changeUnitCount(this)"
                     style=" padding: 10px; text-align: center; font-size: revert; " class=" form-select"
                     id="BS-casesorunits">
                     <option class="text-secondary" value="" selected disabled>Select Case or Units
                     </option>
                     <option value="Unit">Unit</option>
                     <option value="Case">Case</option>
                     <option value="UnitAndCase">Unit + Case</option>
                 </select>
             </div>
             <div class="col-4">
                 <select onchange="BS_changePeriod(this)" name="BS-casesorunits"
                     style=" padding: 10px; text-align: center; font-size: revert; " class=" form-select"
                     id="BS-casesorunits">
                     <option class="text-secondary" value="" selected disabled>Select Chart Period
                         Filter
                     </option>
                     <option value="Daily">Days</option>
                     <option value="Weekly">Weeks</option>
                     <option value="Monthly">Months</option>
                 </select>
             </div>
         </div>

         <canvas id="myChart"></canvas>
     </div>
 </div>
 <div class="col-12 mt-50" style="margin: 50px">
     <h4
         style="color: #2297C3;

         font-family: Inter;
         font-size: 16px;
         font-style: normal;
         font-weight: 900;
         line-height: 24px; /* 150% */">
         Top 10 stores showing Total Stock Level of products </h4>
 </div>
 <div class="col-12">

     <div class="table-responsive" >
             {{-- table-responsive --}}
             {{-- nowrap --}}
         <table id="stockCoutntByStoreDatatable" class="table table-sm  datatable table-hover" style="font-size: 10px; border: 1px solid #ccc; max-width: 80%; ">
             <thead>
                 <tr>
                     <th class="thclass" style=" width: 47.4375px;" scope="col">Date</th>
                     <th class="thclass" scope="col">Name of Store</th>
                     <th class="thclass" scope="col">Location</th>
                     <th class="thclass" scope="col">Category</th>
                     <th class="thclass" scope="col">Product Name</th>
                     <th class="thclass" scope="col">Product Number</th>
                     <th class="thclass" scope="col">Stocks on Shelf (Units)</th>
                     <th class="thclass" scope="col">Stocks on Shelf (Cases)</th>
                     <th class="thclass" scope="col">Stocks Packed (Units)</th>
                     <th class="thclass" scope="col">Stocks Packed (Cases)</th>
                     <th class="thclass" scope="col">Stocks in Storeroom (Units)</th>
                     <th class="thclass" scope="col">Stocks in Storeroom  (Cases)</th>
                     <th class="thclass" scope="col">Total Stocks (units)</th>
                     <th class="thclass" scope="col">Total Stocks (cases)</th>
                     <th class="thclass" scope="col">Total Stock Count</th>
                     <th hidden class="thclass" scope="col">sum Unit+Case</th>
                 </tr>
             </thead>
             <tbody>
             @php
                     $totalHourworked=0;
                     $chartDateArray = array();
                     $chartStockArray = array();
                     $i=1;
                     $shelfUnits=0;
                     $shelfCases=0;
                     $packedUnits= 0;
                     $packedCases=0;
                     $storeRoomUnits= 0;
                     $storeRoomCases=0;

             @endphp
             
             {{-- {{dd($stockCountData)}} --}}
               @if (!$stockCountData->isEmpty())
                 @foreach ($stockCountData as $stockCount)
                 @php
                     // dd($stockCount);
                             if ($stockCount->stock_on_shelf_unit=='Units' || $stockCount->stock_on_shelf_unit=='units')
                             {    
                                 $shelfUnits=$stockCount->stock_on_shelf;
                                 $shelfCases= 0;
                             }
                             else if ($stockCount->stock_on_shelf_unit=='Cases' || $stockCount->stock_on_shelf_unit=='cases')
                             {
                                 $shelfCases= $stockCount->stock_on_shelf;
                                 $shelfUnits=0;
                             }
                             else {
                                 $shelfCases= 0;
                                 $shelfUnits=0;
                             }
                             if ($stockCount->stock_packed_unit=='Units' || $stockCount->stock_packed_unit=='units')
                             {    
                                 $packedUnits=$stockCount->stock_packed;
                                 $packedCases= 0;
                             }
                             else if ($stockCount->stock_packed_unit=='Cases' || $stockCount->stock_packed_unit=='cases')
                             {
                                 $packedCases= $stockCount->stock_packed;
                                 $packedUnits=0;
                             }
                             else {
                                 $packedUnits= 0;
                                 $packedCases=0;
                             }
                             if ($stockCount->stock_in_store_room_unit=='Units' || $stockCount->stock_in_store_room_unit=='units')
                             {    
                                 $storeRoomUnits=$stockCount->stock_in_store_room;
                                 $storeRoomCases= 0;
                             }
                             else if ($stockCount->stock_in_store_room_unit=='Cases' || $stockCount->stock_in_store_room_unit=='cases')
                             {
                                 $storeRoomCases= $stockCount->stock_in_store_room;
                                 $storeRoomUnits=0;
                             }
                             else {
                                 $storeRoomUnits= 0;
                                 $storeRoomCases=0;
                             }
                     
                     $totalStock = $shelfUnits +  $packedUnits + $storeRoomUnits ;
                     $totalStockCases= $shelfCases +$packedCases + $storeRoomCases;
                 @endphp
                     <tr>
                         <td class="tdclass">
                             {{$stockCount->created_at}}
                         </td>
                         <td class="tdclass">{{$stockCount->store->name_of_store}}</td>
                         <td class="tdclass">
                             {{$stockCount->storeLocation->location}}
                         </td>
                        
                         <td class="tdclass">{{$stockCount->category->category}}</td>
                         <td class="tdclass">{{$stockCount->product->product_name}}</td>
                         <td class="tdclass">{{$stockCount->product_sku}}</td>
                         <td class="tdclass">{{$shelfUnits}}</td>
                         <td class="tdclass">{{$shelfCases}}</td>
                         <td class="tdclass">{{$packedUnits}}</td>
                         <td class="tdclass">{{$packedCases}}</td>
                         <td class="tdclass">{{$storeRoomUnits}}</td>
                         <td class="tdclass">{{$storeRoomCases}}</td>
                         <td class="tdclass">{{$totalStock}}</td>
                         <td class="tdclass">{{$totalStockCases}}</td>
                         <td class="tdclass">{{$totalStock}} Units, {{$totalStockCases}} Cases</td>
                         <td hidden class="tdclass">{{$totalStock+$totalStockCases}}</td>
                         @php
                             $sumTotalStock+= $totalStock;
                             $sumTotalStockCases += $totalStockCases;

                             $sumStockUnitCases=  $totalStock+$totalStockCases;
                         @endphp
                     </tr>
                     @php
                         array_push($chartStockArray, ['stock'=>$totalStock, 'date'=>$stockCount->created_at, 'stockCases'=>$totalStockCases, 'sumUnitCase'=>$sumStockUnitCases]);
                    @endphp
                     
                 @endforeach

               @endif                     
                 
             </tbody>
         </table>
     </div>
 </div>
</div>

{{--end business overview Bar chart coming from stock count by store --}}

@php
 $totalHourworked = 0;
 $chartDateArray = [];
 $chartStockArray = [];
 $i = 1;
@endphp

<div class="col-12" style="display: none">

 <div class="table-responsive">
     {{-- table-responsive --}}
     {{-- nowrap --}}
     <table id="businessSummaryDatatable" class="table table-sm  datatable table-hover  "
         style="border: 1px solid #ccc; min-width: 1580px; ">
         <thead>
             <tr>
                 <th class="thclass" style=" width: 47.4375px;" scope="col"> Date</th>

                 <th class="thclass" scope="col">Stock Name of Store</th>
                 <th class="thclass" scope="col"> Location</th>
                 <th class="thclass" scope="col"> Category</th>
                 <th class="thclass" scope="col">Stock Product Name</th>
                 <th class="thclass" scope="col"> Merchandiser</th>
                 <th class="thclass" scope="col">Stock Total Stocks (units)</th>
                 <th class="thclass" scope="col">Stock Total Stocks (cases)</th>

                 <th class="thclass" scope="col">Out_of_stock Name of Store</th>
                 <th class="thclass" scope="col">Out_of_stock Product Name</th>

                 <th class="thclass" scope="col">Product_expiry Name of Store</th>
                 <th class="thclass" scope="col">merchandiser_time_sheet Store</th>
                 <th class="thclass" scope="col">merchandiser_time_sheet Location</th>

                 <th class="thclass" scope="col">sum Unit+Case</th>

             </tr>
         </thead>
         <tbody>
             @php
                 $totalHourworked = 0;
                 $chartDateArray = [];
                 $chartStockArray = [];
                 $i = 1;
                 $shelfUnits = 0;
                 $shelfCases = 0;
                 $packedUnits = 0;
                 $packedCases = 0;
                 $storeRoomUnits = 0;
                 $storeRoomCases = 0;

             @endphp
             {{-- {{dd($outOfStockData)}} --}}
             {{-- {{dd($stockCountData)}} --}}
             @if (!$stockCountData->isEmpty())
                 @foreach ($stockCountData as $stockCount)
                     @php
                         // dd($stockCount);
                         if ($stockCount->stock_on_shelf_unit == 'Units' || $stockCount->stock_on_shelf_unit == 'units') {
                             $shelfUnits = $stockCount->stock_on_shelf;
                             $shelfCases = 0;
                         } elseif ($stockCount->stock_on_shelf_unit == 'Cases' || $stockCount->stock_on_shelf_unit == 'cases') {
                             $shelfCases = $stockCount->stock_on_shelf;
                             $shelfUnits = 0;
                         } else {
                             $shelfCases = 0;
                             $shelfUnits = 0;
                         }
                         if ($stockCount->stock_packed_unit == 'Units' || $stockCount->stock_packed_unit == 'units') {
                             $packedUnits = $stockCount->stock_packed;
                             $packedCases = 0;
                         } elseif ($stockCount->stock_packed_unit == 'Cases' || $stockCount->stock_packed_unit == 'cases') {
                             $packedCases = $stockCount->stock_packed;
                             $packedUnits = 0;
                         } else {
                             $packedUnits = 0;
                             $packedCases = 0;
                         }
                         if ($stockCount->stock_in_store_room_unit == 'Units' || $stockCount->stock_in_store_room_unit == 'units') {
                             $storeRoomUnits = $stockCount->stock_in_store_room;
                             $storeRoomCases = 0;
                         } elseif ($stockCount->stock_in_store_room_unit == 'Cases' || $stockCount->stock_in_store_room_unit == 'cases') {
                             $storeRoomCases = $stockCount->stock_in_store_room;
                             $storeRoomUnits = 0;
                         } else {
                             $storeRoomUnits = 0;
                             $storeRoomCases = 0;
                         }

                         $totalStock = $shelfUnits + $packedUnits + $storeRoomUnits;
                         $totalStockCases = $shelfCases + $packedCases + $storeRoomCases;
                     @endphp
                     <tr>
                         <td class="tdclass">
                             {{-- {{ $stockCount->created_at }} --}}
                         </td>
                         <td class="tdclass">{{ $stockCount->store->name_of_store }}</td>
                         <td class="tdclass">
                             {{ $stockCount->storeLocation->location }}
                         </td>
                         <td class="tdclass">{{ $stockCount->category->category }}</td>
                         <td class="tdclass">{{ $stockCount->product->product_name }}</td>
                         <td class="tdclass">{{ $stockCount->companyUser->user->name }}</td>
                         <td class="tdclass">{{ $totalStock }}</td>
                         <td class="tdclass">{{ $totalStockCases }}</td>
                         <td class="tdclass"></td>
                         <td class="tdclass"></td>
                         <td class="tdclass"></td>

                         <td class="tdclass"></td>
                         <td class="tdclass"></td>
                         <td class="tdclass">{{ $totalStock + $totalStockCases }}</td>

                         @php
                             $sumTotalStock += $totalStock;
                             $sumTotalStockCases += $totalStockCases;

                             $sumStockUnitCases = $totalStock + $totalStockCases;

                         @endphp
                     </tr>
                     @php
                         array_push($chartStockArray, ['stock' => $totalStock, 'date' => $stockCount->created_at, 'stockCases' => $totalStockCases, 'sumUnitCase' => $sumStockUnitCases]);
                     @endphp
                 @endforeach

             @endif
             @if ($outOfStockData != null)
                 @foreach ($outOfStockData as $outOfStock)
                     <tr>
                         <td class="tdclass">
                             {{-- {{ $outOfStock->created_at }} --}}
                         </td>
                         <td class="tdclass">{{ $outOfStock->store->name_of_store }}</td>
                         <td class="tdclass">
                             {{ $outOfStock->storeLocation->location }}
                         </td>
                         <td class="tdclass">{{ $outOfStock->category->category }}</td>
                         <td class="tdclass">{{ $outOfStock->product->product_name }}</td>
                         <td class="tdclass">{{ $outOfStock->companyUser->user->name }}</td>
                         <td class="tdclass"></td>
                         <td class="tdclass"></td>


                         <td class="tdclass">{{ $outOfStock->store->name_of_store }}</td>


                         <td class="tdclass">{{ $outOfStock->product->product_name }}</td>

                         <td class="tdclass"></td>

                         <td class="tdclass"></td>
                         <td class="tdclass"></td>
                         <td class="tdclass"></td>

                     </tr>
                 @endforeach
             @endif
             @if (!$productExpiryTrackerData->isEmpty())
                 @foreach ($productExpiryTrackerData as $productExpiryTracker)
                     <tr>
                         <td class="tdclass">
                             {{-- {{ $productExpiryTracker->created_at }} --}}
                         </td>
                         <td class="tdclass">{{ $productExpiryTracker->store->name_of_store }}</td>
                         <td class="tdclass">
                             {{ $productExpiryTracker->storeLocation->location }}
                         </td>
                         <td class="tdclass">{{ $productExpiryTracker->category->category }}</td>
                         <td class="tdclass">{{ $productExpiryTracker->product->product_name }}</td>
                         <td class="tdclass">{{ $productExpiryTracker->companyUser->user->name }}</td>
                         <td class="tdclass"></td>
                         <td class="tdclass"></td>
                         <td class="tdclass"></td>
                         <td class="tdclass"></td>

                         <td class="tdclass">{{ $productExpiryTracker->store->name_of_store }}</td>
                         <td class="tdclass"></td>

                         <td class="tdclass"></td>
                         <td class="tdclass"></td>

                     </tr>
                 @endforeach
             @endif

             @if (!empty($uniqueServicedStoreLocation))
                 @foreach ($uniqueServicedStoreLocation as $merchandiserLocation)
                     <tr>
                         <td class="tdclass">
                             {{ $merchandiserLocation->created_at }}
                         </td>
                         <td class="tdclass">
                             {{ $merchandiserLocation->store($merchandiserLocation->store_id)->name_of_store }}
                         </td>
                         <td class="tdclass">
                             {{ $merchandiserLocation->store_location($merchandiserLocation->store_location_id ?? null)->location ?? null }}
                         </td>
                         <td class="tdclass"></td>
                         <td class="tdclass"></td>
                         <td class="tdclass">{{ $merchandiserLocation->companyUser->user->name }}</td>
                         <td class="tdclass"></td>
                         <td class="tdclass"></td>
                         <td class="tdclass"></td>
                         <td class="tdclass"></td>
                         <td class="tdclass"></td>

                         <td class="tdclass">
                             {{ $merchandiserLocation->store($merchandiserLocation->store_id)->name_of_store }}
                         </td>
                         <td class="tdclass">
                             {{ $merchandiserLocation->store_location($merchandiserLocation->store_location_id ?? null)->location ?? null }}
                         </td>
                         <td class="tdclass"></td>


                     </tr>
                 @endforeach
             @endif

         </tbody>
     </table>
 </div>
</div>

<script>
 $(document).ready(function() {
     $('[data-toggle="tooltip"]').tooltip({
         placement: 'bottom'
     });
 });
</script>
<script>
 $(document).ready(function() {
     $('.select2').select2();
 });
</script>

<script>

 var startDate = {!! json_encode($startDate) !!};
 var endDate = {!! json_encode($endDate) !!};

 var todayUniqueServicedStoreLocation = {!! json_encode($todayUniqueServicedStoreLocation) !!};
 var uniqueNumberOfStoreServicedCount = {!! json_encode($uniqueNumberOfStoreServicedCount) !!};


 var allStores = {!! json_encode($storesArr) !!};
 var allUniqueLocations = {!! json_encode($locationArr) !!};
 // console.log(allUniqueLocations);
 var sumTotalStockUnit = {!! json_encode($sumTotalStock) !!};
 var sumTotalStockCases = {!! json_encode($sumTotalStockCases) !!};

 var storeServiced = {!! json_encode($uniqueServicedStoreLocation) !!};
 var Stores = {!! json_encode($stores) !!};

 var outOfStockData = {!! json_encode($outOfStockData) !!};
 var products = {!! json_encode($products) !!};

 var productExpiryTrackerData = {!! json_encode($productExpiryTrackerData) !!};



 var graphFormat = 'weeks';
 var graphUnit = 'Unit';

 var labels = [];

 var convertedToChartData = {{ Js::from($chartStockArray) }};
 console.log('convertedToChartData-------------------', convertedToChartData);
</script>


<script src="{{ asset('assets/js/downloadReport.js') }}"></script>
{{-- <script src="{{ asset('assets/js/downloadReport.js') }}"></script> --}}

{{-- @vite(['resources/js/chart.js']) --}}


<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>