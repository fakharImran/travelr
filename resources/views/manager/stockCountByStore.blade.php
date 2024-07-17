@extends('manager.layout.app')
@section('title', 'Stock Count By Store')
@section("top_links")

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


@endsection

@section("bottom_links")

<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    
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


    td, th{
        border: 2px solid #ccc;
        /* padding: 10px; */
    }
    th{
        background-color: #f7f7f7;
        color: #233D79;
    }
    /* Define a CSS class to apply the background image */
</style>
<div class="container stock-count">

    {{-- {{dd($userArr)}} --}}
    <div  class="row d-flex align-items-center col-actions" style="max-width: 99%; margin: 1px auto;">
        <div class="col-md-3 col-3 p-3">
            
            <div class="form-group" >
                <label for="period-search" class="form-label filter period filter-search">Period</label>
                <input type="text" id="period-search" value="Date Range" class=" form-control filter">
                <i class="fas fa-times-circle clear-icon" id="clearDate"></i>

            </div>
        </div>
        <div class="col-md-3 col-3 p-3">
            <div class="form-group">
                <label for="store-search" class="form-label filter store">Select Store</label>
                <select name="store-search" class="filter form-select select2" id="store-search">
                    <option value="" selected>--Select--</option>
                    @if($stores!=null)
                        @foreach ($stores->unique('name_of_store')->sortBy('name_of_store') as $store)
                            <option value="{{$store['name_of_store']}}">{{$store['name_of_store']}}</option>
                        @endforeach
                    @endif
                </select>
                


            </div>
        </div>
        {{-- for setting the filter dropdown unique and sorted value --}}
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
                        array_push($locationArr, $location['location']); 
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
            $locationArr = array_unique($locationArr);
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
                <select name="merchandiser-search" class=" filter form-select select2"  id="merchandiser-search">
                    <option value="" selected>--Select-- </option>
                    @php
                        $uniqueMerchandisers = array_unique(array_column($userArr, 'name'));
                        asort($uniqueMerchandisers); // Sort the array alphabetically

                    @endphp
                    @foreach($uniqueMerchandisers as $merchandiser)
                         <option value="{{$merchandiser}}">{{$merchandiser}}</option>
                    @endforeach
                </select>   
            </div>
        </div>
        <div class="col-md-3 col-3 p-3">
            <div class="form-group">
                <label for="category-search" class="form-label filter category">Select Category</label>
                <select name="category-search" class=" filter form-select select2"  id="category-search">
                    <option value="" selected>--Select-- </option>
                    @foreach($categories->unique('category')->sortBy('category') as $category)
                    <option value="{{$category['category']}}">{{$category['category']}}</option>
                    @endforeach
                </select>   
            </div>
        </div>
        <div class="col-md-3 col-3 p-3">
            <div class="form-group">
                <label for="product-search" class="form-label filter product">Select product</label>
                <select name="product-search" class=" filter form-select select2"  id="product-search">
                    <option value="" selected>--Select-- </option>
                    @foreach($products->unique('product_name')->sortBy('product_name') as $product)
                    <option value="{{$product['product_name']}}">{{$product['product_name']}}</option>
                    @endforeach
                </select>   
            </div>
        </div>
    </div>
    @php
        $sumTotalStock=0;
        $sumTotalStockCases=0;
        $sumStockUnitCases=0;
        $tempStock=0;
    @endphp
    <div class='row  d-flex align-items-center col-actions ' style="max-width: 99%; margin: 1px auto; padding-top:20px">
        <div class="col-md-3 col-3 p-3">
            <div class="card manager-card-style">
                <div class="card-header manager-card-header">Total stock count</div>    
                <div class="card-body">
                    <div  class="content">
                        <div class="row">
                            <div class="col-12" style="color: #37A849;">
                                <h3><b id="total_stock_count_cases">{{$sumTotalStockCases}} </b><sub style="font-size: small;"> Cases</sub></h3>
                            </div>
                            <div class="col-12" style="color: #37A849;">
                                <h3><b id="total_stock_count_unit">{{$sumTotalStock}}  </b><sub style="font-size: small;"> Units</sub></h3>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-3 p-3">
            <div class="card manager-card-style">
                <div class="card-header manager-card-header">Opening Week Stock</div>    
                <div class="card-body">
                    <div  class="content">
                        @php
                            
                        @endphp
                        <small id="opening_week_date" class="text-secondary">
                            @php
                                $sumOpeningWeekStock=0;

                                $sevenDaysAgo = (new DateTime())->sub(new DateInterval('P7D'));
                                $sevenDaysAgo = $sevenDaysAgo->format('Y-m-d');
                                echo $sevenDaysAgo;
                                // dd($stockCountData->isEmpty());
                                if(!$stockCountData->isEmpty())
                            {
                                foreach ($stockCountData as $key => $stockCount) {

                                    $totalStock=$stockCount['stock_on_shelf']+$stockCount['stock_packed']+$stockCount['stock_in_store_room'];
                                    
                                    $date= explode(' ', $stockCount->created_at);
                                    $stockDate= $date[0];

                                    $stockDate = \Carbon\Carbon::parse($stockDate);
                                    $sevenDaysAgo = \Carbon\Carbon::parse($sevenDaysAgo);

                                    // Compare the dates
                                    if ($stockDate->lessThanOrEqualTo($sevenDaysAgo)) {
                                        $sumOpeningWeekStock+= $totalStock;
                                    } 
                                }
                            }

                            @endphp 
                        </small>
                        <div class="row">
                            <div class="col-12" style="color: #37A849;">
                                <h3><b id="opening_week_cases">0</b><sub style="font-size: small;"> Cases</sub></h3>
                            </div>
                            <div class="col-12" style="color: #37A849;">
                                <h3><b id="opening_week_units">0 </b><sub style="font-size: small;"> Units</sub></h3>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-3 p-3">
            <div class="card manager-card-style">
                <div class="card-header manager-card-header">Closing Week Stock</div>    
                <div class="card-body">
                    <div  class="content">
                        <small id="closing_week_date" class="text-secondary">
                            @php
                                $sumClosingWeekStock=0;

                                $todayDate = (new DateTime());
                                echo $todayDate->format('Y-m-d');

                                if(!$stockCountData->isEmpty())
                            {
                                foreach ($stockCountData as $key => $stockCount) {

                                    $totalStock=$stockCount['stock_on_shelf']+$stockCount['stock_packed']+$stockCount['stock_in_store_room'];
                                    
                                    $date= explode(' ', $stockCount->created_at);
                                    $stockDate= $date[0];

                                    $stockDate = \Carbon\Carbon::parse($stockDate);
                                    $todayDate = \Carbon\Carbon::parse($todayDate);

                                    // Compare the dates
                                    if ($stockDate->lessThanOrEqualTo($todayDate)){
                                        $sumClosingWeekStock+= $totalStock;
                                    } 
                                }
                            }

                            @endphp 
                        </small>
                        <div class="row">
                            <div class="col-12" style="color: #37A849;">
                                <h3><b id="closing_week_cases">0</b><sub style="font-size: small;"> Cases</sub></h3>
                            </div>
                            <div class="col-12" style="color: #37A849;">
                                <h3><b id="closing_week_units">0 </b><sub style="font-size: small;"> Units</sub></h3>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-3 p-3">
            <div class="card manager-card-style"  data-toggle="tooltip" title="Average Stock = Opening Week stock / Closing Week Stock">
                <div class="card-header manager-card-header">Average Stock</div>    
                <div class="card-body">
                    @if($sumOpeningWeekStock!= null || $sumClosingWeekStock !=null )
                        <div  class="content" id="average_stock" style="color: #37A849; height: 75px; padding-left: 10px;"><h3><b>0</b></h3></div>
                    @else
                    <div  class="content" id="average_stock"  style="color: #37A849; height: 75px; padding-left: 10px;"><h3><b>0.00%</b></h3></div>

                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row pt-5">
        <div class="col-12">
            <div style="width: 800px; margin: auto;">
                <div class="row d-flex">
                    <div class="col-md-5 col-6">
                        <label for="" class="form-label filter merchandiser">Stock Level of products in store </label>
                    </div>
                    <div class="col-md-3 col-6">
                        <select name="casesorunits" onchange="changeUnitCount(this)"  style=" padding: 10px; text-align: center; font-size: revert; " class=" form-select "  id="casesorunits">
                            <option class="text-secondary" value="" selected disabled>Select Case or Units </option>
                            <option value="Unit">Unit</option>
                            <option value="Case">Case</option>
                            <option value="UnitAndCase">Unit + Case</option>
                        </select>              
                    </div>
                    <div class="col-md-4 col-6">
                        <select onchange="changePeriod(this)" name="periodDisplay" id="periodDisplay" style=" padding: 10px; text-align: center; font-size: revert; "
                         class=" form-select"  id="casesorunits">
                            <option class="text-secondary" value="" selected disabled>Select Chart Period Filter</option>
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
    
    {{-- <div class="row">
        <div class="col-12">
            <button
                class="btn btn-primary btn-sm edit-address"
                style="float: right"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#pendingTimeSheet"
                >
                Pending Time Sheets
            </button>
        </div>
    </div> --}}
    <div class="row pt-5" style="     margin: 1px auto; font-size: 12px;">
        <div class="col-12">
            <div class=" m-3 float-end d-flex">
                <label class="download_filter_label">Download filtered table in excel</label>
                <button id="downloadButton" class="btn btn-light" ><img src="{{ asset('assets/images/managericons/download.svg') }}" alt="Download"></button>
            </div>
        </div>
        <div class="col-12">

            <div class="table-responsive" >
                    {{-- table-responsive --}}
                    {{-- nowrap --}}
                <table id="stockCoutntByStoreDatatable" class="table table-sm  datatable table-hover  " style="border: 1px solid #ccc; min-width: 1580px; ">
                    <thead>
                        <tr>
                            {{-- <th class="thclass" scope="col">Date</th>
                            <th class="thclass" scope="col">Name of Store</th>
                            <th class="thclass" scope="col">Location</th>
                            <th class="thclass" scope="col">Category</th>
                            <th class="thclass" scope="col">Product Name</th>
                            <th class="thclass" scope="col">Merchandiser</th>
                            <th class="thclass" scope="col">Product Number</th>
                            <th class="thclass" scope="col">Stocks on Shelf (Units)</th>
                            <th class="thclass" scope="col">Stocks on Shelf (Cases)</th>
                            <th class="thclass" scope="col">Stocks Packed (Units)</th>
                            <th class="thclass" scope="col">Stocks Packed (Cases)</th>
                            <th class="thclass" scope="col">Stocks in Storeroom (Units)</th>
                            <th class="thclass" scope="col">Stocks in Storeroom  (Cases)</th>
                            <th class="thclass" scope="col">Total Stocks</th>
                             --}}
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
                            <th class="thclass" scope="col">Merchandiser</th>
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
                                <td class="tdclass">{{$stockCount->companyUser->user->name}}</td>
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
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>


{{-- {{dd($chartStockArray);}} --}}
<script>

   
    var startDate= 0;
    var endDate = 0;
    var allStores = {!! json_encode($storesArr) !!};
    var allUniqueLocations = {!! json_encode($locationArr) !!};
    
    var sumTotalStockCases = {!! json_encode($sumTotalStockCases) !!};
    var sumTotalStock = {!! json_encode($sumTotalStock) !!};
    var sumOpeningWeekStock = {!! json_encode($sumOpeningWeekStock) !!};
    var sumClosingWeekStock = {!! json_encode($sumClosingWeekStock) !!};

    var graphFormat = 'weeks';
    var graphUnit = 'Unit';

    var labels = [];

    var convertedToChartData =  {{ Js::from($chartStockArray) }};
    // console.log(convertedToChartData, "chart datwaaaaaa");
</script>

<script src="{{ asset('assets/js/stockCountByStoreDatatable.js') }}"></script>

{{-- @vite(['resources/js/chart.js']) --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#period-search", {
            dateFormat: "M d, Y",
            altFormat: "F j, Y",
            mode: "range",
            });
    });

    
</script>
<script>

function downloadTable(table) {
        const rows = table.getElementsByTagName('tr');
        let csvContent = 'data:text/csv;charset=utf-8,';

        // Add headers as bold and uppercase
        const headers = table.querySelectorAll('thead th');
        const headerText = Array.from(headers)
            .map(header => header.innerText.toUpperCase())
            .join(',');
        csvContent += headerText + '\r\n';


        for (let i = 0; i < rows.length; i++) 
        {
            const cells = rows[i].getElementsByTagName('td');
            for (let j = 0; j < cells.length; j++) {
                const cell = cells[j];
                if (j > 0) {
                    csvContent += ','; // Add a comma as a separator between columns
                }

                const image = cell.querySelector('img');
                if (image) {
                    const imageUrl = image.getAttribute('src');
                    csvContent += cell.innerText +  imageUrl; // Combine text and image URL in the same column
                } else {
                    // csvContent += cell.innerText; // Add the cell's text if there's no image
                    csvContent +="\"" + cell.innerText+ "\""; // Add the cell's text if there's no image

                }
            }
            csvContent += '\r\n';
        }
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', 'Stock_Count_By_Store_table.csv');
        document.body.appendChild(link);
        link.click();
        
    }

    document.getElementById('downloadButton').addEventListener('click', () => {
        const timeSheetTable = document.getElementById('stockCoutntByStoreDatatable');
        downloadTable(timeSheetTable);
    });


$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>


@endsection
