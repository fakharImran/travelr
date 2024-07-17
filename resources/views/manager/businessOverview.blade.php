@extends('manager.layout.app')
@section('title', 'Business Overview')

<head>
    {{-- <script src="https://maps.googleapis.com/maps/api/js?sensor=false&callback=myMap"></script> --}}

</head>

@section('top_links')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>


@endsection

@section('bottom_links')

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <script></script>
@endsection

@section('content')
    <style>
        /* Add appropriate styles for your layout */
        .date-input-container {
            position: relative;
        }

        .clear-icon {
            position: absolute;
            right: -9px;
            top: 56%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #ccc;
        }


        td,
        th {
            border: 2px solid #ccc;
            /* padding: 10px; */
        }

        th {
            background-color: #f7f7f7;
            color: #233D79;
        }

        /* Define a CSS class to apply the background image */
    </style>
    <div class="container business-overview">

        {{-- {{dd($userArr)}} --}}
        <div class="row d-flex align-items-center col-actions" style="max-width: 99%; margin: 1px auto;">
            <div class="col-md-3 col-3 p-3">

          
            <div class="form-group">
                <label for="period-search" class="form-label filter period filter-search">Period</label>
                <input type="text" id="period-search" value="Date Range" class="form-control filter">
                <i class="fas fa-times-circle clear-icon" style="display: none" id="clearDate"></i>
            </div>

           
                
            </div>
            <div class="col-md-3 col-3 p-3">
                <div class="form-group">
                    <label for="store-search" class="form-label filter store">Select Store</label>
                    <select name="store-search" class="filter form-select select2" id="store-search">
                        <option value="" selected>--Select--</option>
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
            <div class="col-md-3 col-3 p-3">
                <div class="form-group">
                    <label for="location-search" class="form-label filter location">Select Location</label>
                    <select name="location-search" class="filter form-select select2" id="location-search">
                        <option value="" selected>--Select--</option>
                        {{-- @foreach ($locationArr as $location)
                        <option value="{{$location}}">{{$location}}</option>
                    @endforeach --}}
                    </select>
                </div>

            </div>
            <div class="col-md-3 col-3 p-3">
                <div class="form-group">
                    <label for="merchandiser-search" class="form-label filter merchandiser">Select Merchandiser</label>
                    <select name="merchandiser-search" class=" filter form-select select2" id="merchandiser-search">
                        <option value="" selected>--Select-- </option>
                        @php
                            $uniqueMerchandisers = array_unique(array_column($userArr, 'name'));
                            asort($uniqueMerchandisers); // Sort the array alphabetically
                        @endphp
                        @foreach ($uniqueMerchandisers as $merchandiser)
                            <option value="{{ $merchandiser }}">{{ $merchandiser }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 col-3 p-3">
                <div class="form-group">
                    <label for="category-search" class="form-label filter category">Select Category</label>
                    <select name="category-search" class=" filter form-select select2" id="category-search">
                        <option value="" selected>--Select-- </option>
                        @foreach ($categories->unique('category')->sortBy('category') as $category)
                            <option value="{{ $category['category'] }}">{{ $category['category'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- {{dd($products)}} --}}
            <div class="col-md-3 col-3 p-3">
                <div class="form-group">
                    <label for="product-search" class="form-label filter product">Select product</label>
                    <select name="product-search" class=" filter form-select select2" id="product-search">
                        <option value="" selected>--Select-- </option>
                        @foreach ($products->unique('product_name')->sortBy('product_name') as $product)
                            <option value="{{ $product['product_name'] }}">{{ $product['product_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>.
        @php
            $totalUniqueProducts= $products->unique('product_name')->count();
            $sumTotalStock = 0;
            $sumTotalStockCases = 0;
            $sumStockUnitCases = 0;
        @endphp
        <div class='row  d-flex align-items-center col-actions' style="max-width: 99%; margin: 1px auto;">
            <div class="col-md-3-5 col-sm-3 col-6 pt-2">
                <div class="card manager-card-style">
                    <div class="card-header manager-card-header">Total stock count</div>
                    <div class="card-body">
                        <div class="content">
                            <div class="row">
                                <div class="col-12" style="color: #37A849;">
                                    <h3><b id="total_stock_count">{{ $sumTotalStockCases }} </b><sub
                                            style="font-size: small;"> Cases</sub></h3>
                                </div>
                                <div class="col-12" style="color: #37A849;">
                                    <h3><b id="total_stock_count_cases">{{ $sumTotalStock }} </b><sub
                                            style="font-size: small;"> Units</sub></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3-5 col-sm-3 col-6 pt-2">
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
                        <div class="Link0" id="serviced_stores"
                            style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                            {{ $uniqueNumberOfStoreServicedCount }} / {{ count($locationArr) }}
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3-5 col-sm-3 col-6 pt-2">
                <div class="card manager-card-style">
                    <div class="card-header manager-card-header">Number of Stores with out of stock</div>
                    <div class="card-body content">
                        <small id="date_range_set" class="text-secondary date_range_set">
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
                        <div class="Link0" id="stores_out_of_stock"
                            style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                            <span style="color: #CA371B">{{ $uniqueStoreCount }}</span> / {{ count($locationArr) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3-5 col-sm-3 col-6 pt-2">
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
                        // dd($products);
                            $totalProducts = $products->unique('product_name')->count();
                            $uniqueProducts = $outOfStockData->unique('product_id')->sort();
                            $uniqueProductCount = $uniqueProducts->count();
                        @endphp
                        <div class="Link0" id="products_out_of_stock"
                            style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                            <span style="color: #CA371B">{{ $uniqueProductCount }}</span> / {{ $totalProducts }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3-5 col-sm-3 col-6 pt-2">
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
                        <div class="Link0" id="stores_with_exp_products"
                            style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                            <span style="color: #CA371B">{{ count($uniqueExpProduct) }} /</span>
                            {{ count($locationArr) }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-12">
                <div style="width: 900px; margin: auto;">
                    <div class="row d-flex">
                        <div class="col-4">
                            <label for="merchandiser-search" class="form-label filter merchandiser">Stock Level of
                                products in store </label>
                        </div>
                        <div class="col-4">
                            <select name="casesorunits" onchange="changeUnitCount(this)"
                                style=" padding: 10px; text-align: center; font-size: revert; " class=" form-select"
                                id="casesorunits">
                                <option class="text-secondary" value="" selected disabled>Select Case or Units
                                </option>
                                <option value="Unit">Unit</option>
                                <option value="Case">Case</option>
                                <option value="UnitAndCase">Unit + Case</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <select onchange="changePeriod(this)" name="casesorunits"
                                style=" padding: 10px; text-align: center; font-size: revert; " class=" form-select"
                                id="casesorunits">
                                <option class="text-secondary" value="" selected disabled>Select Chart Period Filter
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
        </div>
       
        <div class="row pt-5">
                <div class="col-12">
                <div class="card manager-card-style">
                    <div class="card-header manager-card-header" >Number of Stores serviced by Channel</div>
                    <div class="card-body">
                        <div class='row  d-flex align-items-center col-actions' style="max-width: 99%; margin: 1px auto;">
                            <div class="col-md-3 col-sm-3 col-6 pt-2">
                                <div class="card manager-card-style"  style="min-height: 133px;">
                                    <div class="card-header manager-card-header text-center mt-3">Supermarket</div>
                                    <div class="card-body content w-100 text-center">
                                        <div class="Link0" id="stores_out_of_stock"
                                            style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                                            <span>{{ $totalNumberServicedChannelbyLocation['supermarket']??0 }}</span> / {{ $locationChannelTotalCount['supermarket']??0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-6 pt-2">
                                <div class="card manager-card-style"  style="min-height: 133px;">
                                    <div class="card-header manager-card-header text-center mt-3">Wholesale</div>
                                    <div class="card-body content w-100 text-center">
                                        <div class="Link0" id="products_out_of_stock"
                                            style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                                            <span>{{ $totalNumberServicedChannelbyLocation['wholesale']??0 }}</span> /  {{ $locationChannelTotalCount['wholesale']??0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-6 pt-2">
                                <div class="card manager-card-style"  style="min-height: 133px;">
                                    <div class="card-header manager-card-header text-center mt-3">Bar</div>
                                    <div class="card-body content w-100 text-center">
                                        <div class="Link0" id="stores_out_of_stock"
                                            style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                                            <span>{{ $totalNumberServicedChannelbyLocation['bar']??0 }}</span> /  {{ $locationChannelTotalCount['bar']??0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-6 pt-2">
                                <div class="card manager-card-style"  style="min-height: 133px;">
                                    <div class="card-header manager-card-header text-center mt-3">Pharmacy</div>
                                    <div class="card-body content w-100 text-center">
                                        <div class="Link0" id="products_out_of_stock"
                                            style="width: 100%; height: 100%; color: #37A849; font-size: 35px; font-family: Inter; font-weight: 700; line-height: 37.50px; word-wrap: break-word">
                                            <span>{{ $totalNumberServicedChannelbyLocation['pharmacy']??0 }}</span> /  {{ $locationChannelTotalCount['pharmacy']??0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mt-5">
                        <img src="{{asset('/assets/images/mapicons/map.png')}}" style="width: 931px; margin-bottom: 15%;">
                        <div class="hanover" style="position:absolute; left:0; top:0;">
                            <img src="{{asset('/assets/images/mapicons/supermarket.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['hanover_supermarket']??0}} Supermarkets in Hanover" style="z-index: 999; position: absolute; left: 69px; width: 23px; top: 74px; display:{{$parishChannelCount['hanover_supermarket']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/wholesale.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['hanover_wholesale']??0}} Wholesales in Hanover"  style="z-index: 999; position: absolute; left: 40px; width: 23px; top: 79px; display:{{$parishChannelCount['hanover_wholesale']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/bar.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['hanover_bar']??0}} Bars in Hanover"  style="z-index: 999; position: absolute; left: 144px; width: 23px; top: 50px; display:{{$parishChannelCount['hanover_bar']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/pharmacy.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['hanover_pharmacy']??0}} Pharmacy in Hanover"  style="z-index: 999; position: absolute; left: 115px; width: 23px; top: 50px; display:{{$parishChannelCount['hanover_pharmacy']??'none'}};">
                        </div>
                        <div class="stjames" style="position:absolute; left:124; top:0;">
                            <img src="{{asset('/assets/images/mapicons/supermarket.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stjames_supermarket']??0}} Supermarkets in Saint James"  style="z-index: 999; position: absolute; left: 116px; width: 23px; top: 90px; display:{{$parishChannelCount['stjames_supermarket']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/wholesale.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stjames_wholesale']??0}} Wholesales in Saint James"  style="z-index: 999; position: absolute; left: 86px; width: 23px; top: 90px; display:{{$parishChannelCount['stjames_wholesale']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/bar.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stjames_bar']??0}} Bars in Saint James"  style="z-index: 999; position: absolute; left: 85px; width: 23px; top: 50px; display:{{$parishChannelCount['stjames_bar']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/pharmacy.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stjames_pharmacy']??0}} Pharmacy in Saint James"  style="z-index: 999; position: absolute; left: 115px; width: 23px; top: 50px; display:{{$parishChannelCount['stjames_pharmacy']??'none'}};">
                        </div>
                        <div class="trelawny" style="position:absolute; left:300; top:0;">
                            <img src="{{asset('/assets/images/mapicons/supermarket.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['trelawny_supermarket']??0}} Supermarkets in Trelawny"  style="z-index: 999; position: absolute; left: 55; width: 23px; top: 100px; display:{{$parishChannelCount['trelawny_supermarket']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/wholesale.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['trelawny_wholesale']??0}} Wholesales in Trelawny"  style="z-index: 999; position: absolute; left: 1px; width: 23px; top: 100px; display:{{$parishChannelCount['trelawny_wholesale']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/bar.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['trelawny_bar']??0}} Bars in Trelawny"  style="z-index: 999; position: absolute; left: 0px; width: 23px; top: 50px; display:{{$parishChannelCount['trelawny_bar']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/pharmacy.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['trelawny_pharmacy']??0}} Pharmacy in Trelawny"  style="z-index: 999; position: absolute; left: 55; width: 23px; top: 50px; display:{{$parishChannelCount['trelawny_pharmacy']??'none'}};">
                        </div>
                        <div class="stann" style="position:absolute; left:437; top:19;">
                            <img src="{{asset('/assets/images/mapicons/supermarket.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stann_supermarket']??0}} Supermarkets in Saint Ann"  style="z-index: 999; position: absolute; left: 60; width: 23px; top: 100px; display:{{$parishChannelCount['stann_supermarket']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/wholesale.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stann_wholesale']??0}} Wholesales in Saint Ann"  style="z-index: 999; position: absolute; left: 1px; width: 23px; top: 100px; display:{{$parishChannelCount['stann_wholesale']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/bar.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stann_bar']??0}} Bars in Saint Ann"  style="z-index: 999; position: absolute; left: 0px; width: 23px; top: 50px; display:{{$parishChannelCount['stann_bar']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/pharmacy.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stann_pharmacy']??0}} Pharmacy in Saint Ann"  style="z-index: 999; position: absolute; left: 60; width: 23px; top: 50px; display:{{$parishChannelCount['stann_pharmacy']??'none'}};">
                        </div>
                        <div class="stmary" style="position:absolute; left:619; top:34;">
                            <img src="{{asset('/assets/images/mapicons/supermarket.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stmary_supermarket']??0}} Supermarkets in Saint Mary"  style="z-index: 999; position: absolute; left: 46px; width: 23px; top: 92px; display:{{$parishChannelCount['stmary_supermarket']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/wholesale.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stmary_wholesale']??0}} Wholesales in Saint Mary"  style="z-index: 999; position: absolute; left: 10px; width: 23px; top: 92px; display:{{$parishChannelCount['stmary_wholesale']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/bar.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stmary_bar']??0}} Bars in Saint Mary"  style="z-index: 999; position: absolute; left: 0px; width: 23px; top: 50px; display:{{$parishChannelCount['stmary_bar']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/pharmacy.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stmary_pharmacy']??0}} Pharmacy in Saint Mary"  style="z-index: 999; position: absolute; left: -31px; width: 23px; top: 50px; display:{{$parishChannelCount['stmary_pharmacy']??'none'}};">
                        </div>
                        <div class="portland" style="position:absolute; left:755; top:120;">
                            <img src="{{asset('/assets/images/mapicons/supermarket.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['portland_supermarket']??0}} Supermarkets in Portland"  style="z-index: 999; position: absolute; left: 115; width: 23px; top: 90; display:{{$parishChannelCount['portland_supermarket']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/wholesale.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['portland_wholesale']??0}} Wholesales in Portland"  style="z-index: 999; position: absolute; left: 88; width: 23px; top: 70; display:{{$parishChannelCount['portland_wholesale']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/bar.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['portland_bar']??0}} Bars in Portland"  style="z-index: 999; position: absolute; left: 0px; width: 23px; top: 50px; display:{{$parishChannelCount['portland_bar']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/pharmacy.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['portland_pharmacy']??0}} Pharmacy in Portland"  style="z-index: 999; position: absolute; left: -31px; width: 23px; top: 50px; display:{{$parishChannelCount['portland_pharmacy']??'none'}};">
                        </div>
                        <div class="westmoreland" style="position:absolute; left:54; top:73;">
                            <img src="{{asset('/assets/images/mapicons/supermarket.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['westmoreland_supermarket']??0}} Supermarkets in Westmoreland"  style="z-index: 999; position: absolute; left: 115; width: 23px; top: 92; display:{{$parishChannelCount['westmoreland_supermarket']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/wholesale.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['westmoreland_wholesale']??0}} Wholesales in Westmoreland"  style="z-index: 999; position: absolute; left: 103; width: 23px; top: 60; display:{{$parishChannelCount['westmoreland_wholesale']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/bar.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['westmoreland_bar']??0}} Bars in Westmoreland"  style="z-index: 999; position: absolute; left: 0px; width: 23px; top: 50px; display:{{$parishChannelCount['westmoreland_bar']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/pharmacy.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['westmoreland_pharmacy']??0}} Pharmacy in Westmoreland"  style="z-index: 999; position: absolute; left: -31px; width: 23px; top: 50px; display:{{$parishChannelCount['westmoreland_pharmacy']??'none'}};">
                        </div>
                        <div class="stelizabeth" style="position:absolute; left:244; top:157;">
                            <img src="{{asset('/assets/images/mapicons/supermarket.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stelizabeth_supermarket']??0}} Supermarkets in Saint Elizabeth"  style="z-index: 999; position: absolute; left: 46; width: 23px; top: 81; display:{{$parishChannelCount['stelizabeth_supermarket']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/wholesale.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stelizabeth_wholesale']??0}} Wholesales in Saint Elizabeth"  style="z-index: 999; position: absolute; left: 1px; width: 23px; top: 50; display:{{$parishChannelCount['stelizabeth_wholesale']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/bar.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stelizabeth_bar']??0}} Bars in Saint Elizabeth"  style="z-index: 999; position: absolute; left: 0px; width: 23px; top: 0; display:{{$parishChannelCount['stelizabeth_bar']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/pharmacy.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stelizabeth_pharmacy']??0}} Pharmacy in Saint Elizabeth"  style="z-index: 999; position: absolute; left: 40; width: 23px; top: 0; display:{{$parishChannelCount['stelizabeth_pharmacy']??'none'}};">
                        </div>
                        <div class="manchester" style="position:absolute; left:336; top:186;">
                            <img src="{{asset('/assets/images/mapicons/supermarket.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['manchester_supermarket']??0}} Supermarkets in Manchester"  style="z-index: 999; position: absolute; left: 51px; width: 23px; top: 50; display:{{$parishChannelCount['manchester_supermarket']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/wholesale.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['manchester_wholesale']??0}} Wholesales in Manchester"  style="z-index: 999; position: absolute; left: 11px; width: 23px; top: 50; display:{{$parishChannelCount['manchester_wholesale']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/bar.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['manchester_bar']??0}} Bars in Manchester"  style="z-index: 999; position: absolute; left: 0px; width: 23px; top: -30; display:{{$parishChannelCount['manchester_bar']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/pharmacy.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['manchester_pharmacy']??0}} Pharmacy in Manchester"  style="z-index: 999; position: absolute; left: 30px; width: 23px; top: 0; display:{{$parishChannelCount['manchester_pharmacy']??'none'}};">
                        </div>
                        <div class="clarendon" style="position:absolute; left:439; top:168;">
                            <img src="{{asset('/assets/images/mapicons/supermarket.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['clarendon_supermarket']??0}} Supermarkets in Clarendon"  style="z-index: 999; position: absolute; left: 49; width: 23px; top: 119; display:{{$parishChannelCount['clarendon_supermarket']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/wholesale.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['clarendon_wholesale']??0}} Wholesales in Clarendon"  style="z-index: 999; position: absolute; left: 1px; width: 23px; top: 73; display:{{$parishChannelCount['clarendon_wholesale']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/bar.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['clarendon_bar']??0}} Bars in Clarendon"  style="z-index: 999; position: absolute; left: -19; width: 23px; top: 0; display:{{$parishChannelCount['clarendon_bar']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/pharmacy.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['clarendon_pharmacy']??0}} Pharmacy in Clarendon"  style="z-index: 999; position: absolute; left: 30px; width: 23px; top: 0; display:{{$parishChannelCount['clarendon_pharmacy']??'none'}};">
                        </div>
                        <div class="stcatherine" style="position:absolute; left:542; top:196;">
                            <img src="{{asset('/assets/images/mapicons/supermarket.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stcatherine_supermarket']??0}} Supermarkets in Saint Catherine"  style="z-index: 999; position: absolute; left: 9; width: 23px; top: 58; display:{{$parishChannelCount['stcatherine_supermarket']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/wholesale.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stcatherine_wholesale']??0}} Wholesales in Saint Catherine"  style="z-index: 999; position: absolute; left: 1px; width: 23px; top: 30; display:{{$parishChannelCount['stcatherine_wholesale']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/bar.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stcatherine_bar']??0}} Bars in Saint Catherine"  style="z-index: 999; position: absolute; left: 0px; width: 23px; top: 0; display:{{$parishChannelCount['stcatherine_bar']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/pharmacy.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stcatherine_pharmacy']??0}} Pharmacy in Saint Catherine"  style="z-index: 999; position: absolute; left: 40; width: 23px; top: 8; display:{{$parishChannelCount['stcatherine_pharmacy']??'none'}};">
                        </div>
                        <div class="standrew" style="position:absolute; left:656; top:188;">
                            <img src="{{asset('/assets/images/mapicons/supermarket.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['standrew_supermarket']??0}} Supermarkets in Saint Andrew"  style="z-index: 999; position: absolute; left: 27; width: 23px; top: 39; display:{{$parishChannelCount['standrew_supermarket']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/wholesale.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['standrew_wholesale']??0}} Wholesales in Saint Andrew"  style="z-index: 999; position: absolute; left: 1px; width: 23px; top: 39; display:{{$parishChannelCount['standrew_wholesale']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/bar.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['standrew_bar']??0}} Bars in Saint Andrew"  style="z-index: 999; position: absolute; left: 0px; width: 23px; top: 0; display:{{$parishChannelCount['standrew_bar']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/pharmacy.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['standrew_pharmacy']??0}} Pharmacy in Saint Andrew"  style="z-index: 999; position: absolute; left: 30px; width: 23px; top: 0; display:{{$parishChannelCount['standrew_pharmacy']??'none'}};">
                        </div>
                        <div class="kingston" style="position:absolute; left:673; top:250;">
                            <img src="{{asset('/assets/images/mapicons/supermarket.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['kingston_supermarket']??0}} Supermarkets in Kingston"  style="z-index: 999; position: absolute; left: 30; width: 23px; top: 23; display:{{$parishChannelCount['kingston_supermarket']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/wholesale.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['kingston_wholesale']??0}} Wholesales in Kingston"  style="z-index: 999; position: absolute; left: 0px; width: 23px; top: 23; display:{{$parishChannelCount['kingston_wholesale']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/bar.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['kingston_bar']??0}} Bars in Kingston"  style="z-index: 999; position: absolute; left: 0px; width: 23px; top: 0; display:{{$parishChannelCount['kingston_bar']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/pharmacy.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['kingston_pharmacy']??0}} Pharmacy in Kingston"  style="z-index: 999; position: absolute; left: 30px; width: 23px; top: 0; display:{{$parishChannelCount['kingston_pharmacy']??'none'}};">
                        </div>
                        <div class="stthomas" style="position:absolute; left:766; top:231;">
                            <img src="{{asset('/assets/images/mapicons/supermarket.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stthomas_supermarket']??0}} Supermarkets in Saint Thomas"  style="z-index: 999; position: absolute; left: 114; width: 23px; top: 32; display:{{$parishChannelCount['stthomas_supermarket']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/wholesale.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stthomas_wholesale']??0}} Wholesales in Saint Thomas"  style="z-index: 999; position: absolute; left: 67; width: 23px; top: 32; display:{{$parishChannelCount['stthomas_wholesale']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/bar.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stthomas_bar']??0}} Bars in Saint Thomas"  style="z-index: 999; position: absolute; left: 0px; width: 23px; top: 0; display:{{$parishChannelCount['stthomas_bar']??'none'}};">
                            <img src="{{asset('/assets/images/mapicons/pharmacy.png')}}" data-toggle="tooltip" title="{{$parishChannelCount['stthomas_pharmacy']??0}} Pharmacy in Saint Thomas"  style="z-index: 999; position: absolute; left: 15; width: 23px; top: 37; display:{{$parishChannelCount['stthomas_pharmacy']??'none'}};">
                        </div>
                        <img src="{{asset('/assets/images/mapicons/tips.PNG')}}"  style="width: 30%; position: absolute; left: 0; top: 57%;" >
                    </div>
                </div>
            </div>
        </div>
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
                <table id="businessOverviewDatatable" class="table table-sm  datatable table-hover  "
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
                        @if (!$stockCountData->isEmpty())
                            @foreach ($stockCountData as $stockCount)
                                @php
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
                                        {{ $stockCount->created_at }}
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
                                        {{ $outOfStock->created_at }}
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
                                        {{ $productExpiryTracker->created_at }}
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
                                        {{ $merchandiserLocation->store_location($merchandiserLocation->store_location_id??null)->location ?? null }}
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
                                        {{ $merchandiserLocation->store_location($merchandiserLocation->store_location_id??null)->location ?? null }}
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
            $(document).ready(function(){
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
            var startDate = 0;
            var endDate = 0;

            
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
            var totalUniqueProducts = {!!json_encode($totalUniqueProducts)!!};

            console.log('totalUniqueProducts----------------', totalUniqueProducts);
            var productExpiryTrackerData = {!! json_encode($productExpiryTrackerData) !!};



            var graphFormat = 'weeks';
            var graphUnit = 'Unit';

            var labels = [];

            var convertedToChartData = {{ Js::from($chartStockArray) }};
            console.log('convertedToChartData-------------------', convertedToChartData);
        </script>


        <script src="{{ asset('assets/js/businessOverviewDatatableAndChart.js') }}"></script>

        {{-- @vite(['resources/js/chart.js']) --}}

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                flatpickr("#period-search", {
                    dateFormat: "M d, Y",
                    altFormat: "F j, Y",
                    mode: "range",
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                // Function to check for ',' in the input value and show/hide the clear icon
                function checkForComma() {
                    var inputValue = $('#period-search').val();
                    if (inputValue.includes(',')) {
                        $('#clearDate').show();
                    } else {
                        $('#clearDate').hide();
                    }
                }

                // Event listener for input field
                $('#period-search').on('input', function() {
                    checkForComma();
                });

                // Event listener for clear icon
                $('#clearDate').on('click', function() {
                    $('#period-search').val('Date Range'); // Clear the input value
                    $(this).hide(); // Hide the clear icon
                });

                // Initial check when the page loads
                checkForComma();
            });
        </script>


    @endsection
