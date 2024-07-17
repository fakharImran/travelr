@extends('manager.layout.app')
@section('title', 'Price Audit Data')

@section("top_links")

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">


<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


@endsection

@section("bottom_links")

<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    
<script>

</script>
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
<div class="container price-audit">

    <div  class="row d-flex align-items-center col-actions" style="   max-width: 99%; margin: 1px auto;">
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 p-3">
            
            <div class="form-group" >
                <label for="period-search" class="form-label filter period filter-search">Period</label>
                <input type="text" id="period-search" value="Date Range" class=" form-control filter">
                <i class="fas fa-times-circle clear-icon" id="clearDate"></i>

            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 p-3">
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
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 p-3">
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
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 p-3">
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
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 p-3">
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
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 p-3">
            <div class="form-group">
                <label for="product-search" class="form-label filter product">Select product</label>
                <select name="product-search" onchange="getProductData(this)" class=" filter form-select select2"  id="product-search">
                    <option value="" selected>--Select-- </option>
                    @foreach($products->unique('product_name')->sortBy('product_name') as $product)
                    <option value="{{$product['product_name']}}">{{$product['product_name']}}</option>
                    @endforeach
                </select>   
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="card manager-card-style-header "  data-toggle="tooltip" title="Price Comparison index = ((Store Price ➗ Competitor Product Price) x 100) - 100">
                    <div class="card-header manager-card-header">Price Comparison Index</div>    
                    <div class="card-body">
                        <div class="percentage" id="price_comparison" style="font-size: 35px;">

                        </div>
                    </div>     
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 " style="padding-top:41px;">

            <div style=" padding-bottom: 14px; pt-10; font-size: small;">
                <label for=""><b style="    color: #929293">= Equal Than Competitor Average Price  </b></label>
                <label for=""><b style="    color: #1892C0"> > Greater Than Competitor Average Price</b></label>
                <label for=""><b style="    color: #1BC018;">< Less Than Competitor Average Price</b></label>
            </div>
        </div>
    </div>
    <div style="width: 800px; margin: auto;">
        <canvas id="myChart"></canvas>
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
    <div class="row pt-5" style=" font-size: 15px; line-height: normal;">
        <div class="col-5">

            <div >
                <h5 class="fw-bolder" >Legend</h5>
            </div>
            <div class="d-flex mt-2 mb-2">
                <div style="background-color: #1BC018;" class="bullet"></div> <div> Your Product</div>
            </div>
            <div class="d-flex mt-2 mb-2">
                <div   style="background-color: #1892C0;" class="bullet"></div> <div>Your Competitor Product</div>
            </div>
            
            {{-- <div class=" bullet" style="background-color: green;"> Your Product</div><br>
            <div class= "bullet"  style="background-color: blue;"> Your Compititor Product</div> --}}
        </div>
            @php
                $minProdPrice=0;
                $maxProdPrice=0;
                $avgProdPrice=0;
                $avgCompProdPrice=0;
            @endphp
        

    </div>
    <div class="row pt-5" style=" ">

        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 ">
            <div class="card manager-card-style m-1">
                <div class="card-header manager-card-header">Max. Product Price</div>    
                <div class="card-body">
                    <div class="percentage" id="maxProductPrice">$0</div>
                </div>     
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 ">
            <div class="card manager-card-style m-1">
                <div class="card-header manager-card-header">Min. Product Price</div>    
                <div class="card-body">
                    <div class="percentage" id="minProductPrice">$0</div>
                </div>     
            </div>
        </div> 
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 ">
            <div class="card manager-card-style m-1"  data-toggle="tooltip" title="Average Price = Sum of store price to date ➗ Number of Stores to date For example (($75 + $34 + $25 + $10) ➗ 4 stores) = $85">
                <div class="card-header manager-card-header">Average Product Price</div>    
                <div class="card-body">
                    <div class="percentage" id="averageProductPrice">$0</div>
                </div>     
            </div>
        </div> 
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 ">
            <div class="card manager-card-style m-1"  data-toggle="tooltip" title="Competitor Average Price = Sum of Competitor Product price to date ➗ Number of Stores to date For example (($65 + $20 + $30 + $50) ➗ 4 stores) = $41.25">
                <div class="card-header manager-card-header">Competitor Product Average Price</div>    
                <div class="card-body">
                    <div class="percentage" id="compititorProductPrice">$0</div>
                </div>     
            </div>
        </div>

    </div>

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
                <table id="pricaAuditDatatable" class="table table-sm  datatable table-hover  " style="border: 1px solid #ccc; min-width: 1580px; ">
                    <thead>
                        <tr>
                            <th class="thclass" scope="col">Date</th>
                            <th class="thclass" scope="col">Name of Store</th>
                            <th class="thclass" scope="col">Location</th>
                            <th class="thclass" scope="col">Category</th>
                            <th class="thclass" scope="col">Product Name</th>
                            <th class="thclass" scope="col">Product Number/SKU</th>
                            <th class="thclass" scope="col">Store Price</th>
                            <th class="thclass" scope="col">Tax</th>
                            <th class="thclass" scope="col">Total Price</th>
                            <th class="thclass" scope="col">Competitor Product Name</th>
                            <th class="thclass" scope="col">Competitor Product Price</th>
                            <th class="thclass" scope="col">Competitor Product Tax</th>
                            <th class="thclass" scope="col">Total Competitor Price</th>

                            <th class="thclass" scope="col">Merchandiser</th>
                            <th class="thclass" scope="col">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                            $totalHourworked=0;
                            $chartDateArray = array();
                            $chartHoursArray = array();

                            $products_name = array();
                            $our_products_price = array();
                            $competitor_products_price = array();
                    @endphp
                       {{-- @php
                           array_push($chartHoursArray ,['product name'=>"first_product", 'price'=>50] );
                       @endphp  --}}

                        @if (!$priceAuditData->isEmpty())
                            @foreach ($priceAuditData as $priceAudit)
                                <tr>
                                    {{-- {{dd($priceAudit)}} --}}
                                    <td class="tdclass">
                                        @php
                                            $date= explode(' ', $priceAudit->created_at);
                                        @endphp
                                        {{$priceAudit->created_at}}
                                    </td>
                                    <td class="tdclass">{{$priceAudit->store->name_of_store}}</td>
                                    <td class="tdclass">
                                        {{$priceAudit->storeLocation->location}}
                                    </td>
                                    
                                    <td class="tdclass">{{$priceAudit->category->category}}</td>
                                    <td class="tdclass">{{$priceAudit->product->product_name}}</td>
                                    <td class="tdclass">{{$priceAudit->Product_SKU}}</td>

                                    <td class="tdclass">{{number_format($priceAudit->product_store_price, 2)}}</td>
                                   
                                    <td class="tdclass">
                                        @php
                                            $taxAmount= ($priceAudit->product_store_price/100) * $priceAudit->tax_in_percentage;
                                        @endphp
                                        {{ number_format($taxAmount, 2) }}
                                    </td>
                                    <td class="tdclass">
                                        @php
                                            $totalPrice= $priceAudit->product_store_price + $priceAudit->product_store_price/100 * $priceAudit->tax_in_percentage;
                                            // echo $totalPrice;
                                            $totalPrice= number_format($totalPrice, 2);
                                            echo $totalPrice;
                                        @endphp
                                    </td>
                                    <td class="tdclass">{{$priceAudit->competitor_product_name}}</td>
                                    
                                    <td class="tdclass">{{number_format($priceAudit->competitor_product_price, 2)}}</td>
                                    <td class="tdclass">
                                        @php
                                            $taxAmount= ($priceAudit->competitor_product_price/100) * $priceAudit->competitor_product_tax;
                                        @endphp
                                        {{ number_format($taxAmount, 2) }}
                                    </td>
                                    <td class="tdclass">
                                        @php
                                            $totalCompetetorPrice= $priceAudit->competitor_product_price + $priceAudit->competitor_product_price/100 * $priceAudit->competitor_product_tax;
                                            // echo $totalCompetetorPrice;
                                            $totalCompetetorPrice= number_format($totalCompetetorPrice, 2);
                                            echo $totalCompetetorPrice;

                                        @endphp
                                    </td>
                                    <td class="tdclass">{{$priceAudit->companyUser->user->name}}</td>

                                    <td class="tdclass">{{$priceAudit->notes}}</td>
                                </tr>
                                @php
                                        // $sumStorePrice+= $priceAudit->product_store_price;
                                        // $sumCompititorProductPrice+= $priceAudit->competitor_product_price;
                                        // $priceComparison=number_format((($sumStorePrice/$sumCompititorProductPrice)*100)-100, 2);
                                   array_push( $products_name,[$priceAudit->product->product_name, $priceAudit->competitor_product_name]);
                                    array_push($our_products_price, $totalPrice);
                                    array_push($competitor_products_price ,  $totalCompetetorPrice);
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

<script>
    var startDate= 0;
    var endDate = 0;
    var allStores = {!! json_encode($storesArr) !!};
    var allUniqueLocations = {!! json_encode($locationArr) !!};
    var allProducts = {!! json_encode($products) !!};
    var priceAuditData = {!! json_encode($priceAuditData) !!};
    
    var products_name = [];
    var products_price = [];
    var labels = [];
   
// console.log('productsss', products_name, our_products_price, competitor_products_price);
    var chartData =  {{ Js::from($chartHoursArray) }};
    // console.log(chartData, "chart datwaaaaaa");
</script>

<script src="{{ asset('assets/js/priceAuditDataTableAndChart.js') }}"></script>

{{-- @vite(['resources/js/chart.js']) --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#period-search", {
            dateFormat: "M d, Y",
            altFormat: "F j, Y",
            mode: "range",
            });
    });

    function getProductData(data) {
    console.log(data.value);

    // // Convert relevant array elements from string to number
    // var value6 = parseFloat(data.value[0][6]);  // Assuming it's a float
    // var value10 = parseFloat(data[0][10]);  // Assuming it's a float

    // // Check if the conversion was successful
    // if (isNaN(value6) || isNaN(value10)) {
    //     console.error('Unable to convert some values to numbers.');
    //     return;
    // }

    // var average = ((value6 / value10) * 100) - 100;
    // console.log(average, 'avg');
}


    
    // document.getElementById('clearDate').addEventListener('click', function () {
    //     document.getElementById('period-search').clear;
    //     // document.getElementById('merchandiser-search').value='';
    //     // document.getElementById('location-search').value='';
    //     // document.getElementById('store-search').value='';
        
    //     // convertingData(chartData);
    //     // myChartJS.data.labels = labels;
    //     // myChartJS.data.datasets[0].data = hoursWorked;
    //     // myChartJS.update(); 
    //     document.getElementById('period-search').value= 'Date Range';

    // });
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
                    csvContent +="\"" + cell.innerText+ "\""; // Add the cell's text if there's no image
                    // csvContent += cell.innerText; // Add the cell's text if there's no image
                }
            }
            csvContent += '\r\n';
        }
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', 'Price_Audit_table.csv');
        document.body.appendChild(link);
        link.click();
        
    }

    document.getElementById('downloadButton').addEventListener('click', () => {
        const timeSheetTable = document.getElementById('pricaAuditDatatable');
        downloadTable(timeSheetTable);
    });

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>

@endsection
