@extends('manager.layout.app')
@section('title', 'Planogram Compliance Tracker')

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
<div class="container planogram">

    {{-- {{dd($userArr)}} --}}
    <div  class="row d-flex align-items-center col-actions" style="   max-width: 99%; margin: 1px auto;">
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
    <div class="row pt-5" style="     margin: 1px auto; font-size: 12px;">
        <div class="col-12">
            <div class="  m-3 float-end d-flex">
                <label class="download_filter_label">Download filtered table in excel</label>
                <button id="downloadButton" class="btn btn-light" ><img src="{{ asset('assets/images/managericons/download.svg') }}" alt="Download"></button>
            </div>
        </div>
        <div class="col-12">

            <div class="table-responsive" >
                    {{-- table-responsive --}}
                    {{-- nowrap --}}
                <table id="planogramComplianceTrackerDatatable" class="table table-sm  datatable table-hover  " style="border: 1px solid #ccc; min-width: 1580px; ">
                    <thead>
                        <tr>
                            <th class="thclass" scope="col">Date</th>
                            <th class="thclass" scope="col">Name of Store</th>
                            <th class="thclass" scope="col">Location</th>
                            <th class="thclass" scope="col">Category</th>
                            <th class="thclass" scope="col">Product Name</th>
                            <th class="thclass" scope="col">Product Number/SKU</th>
                            <th class="thclass" scope="col">Is Planogram Compliance</th>
                            <th class="thclass" scope="col">Before Photo</th>
                            <th class="thclass" scope="col">After Photo</th>
                            <th class="thclass" scope="col">Action</th>
                            <th class="thclass" scope="col">Merchandiser</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                            $totalHourworked=0;
                            $chartDateArray = array();
                            $chartHoursArray = array();
                    @endphp
                        @if ($planogramComplianceData!=null)
                        @foreach ($planogramComplianceData as $planogram)
                        <tr>
                            <td class="tdclass">
                                @php
                                    $date= explode(' ', $planogram->created_at);
                                @endphp
                                {{$date[0]}}
                            </td>
                            <td class="tdclass">{{$planogram->store->name_of_store}}</td>
                            <td class="tdclass">
                                {{$planogram->storeLocation->location}}
                            </td>
                            
                            <td class="tdclass">{{$planogram->category->category}}</td>
                            <td class="tdclass">{{$planogram->product->product_name}}</td>
                            <td class="tdclass">{{$planogram->product_number_sku}}</td>
                            <td class="tdclass">{{$planogram->is_planogram_compliance}}</td>

                            <td class="tdclass">
                                @php
                                    if($planogram->photo_before_stocking_shelf!=null)
                                    {
                                        $imagePath = asset('storage/' . $planogram->photo_before_stocking_shelf);
                                        echo "<img width='100' src='$imagePath' onclick='displayFullScreenImage(\"$imagePath\")' />";


                                    }
                                    else 
                                    {
                                        echo "N/A";
                                    }
                                @endphp     
                            </td>
                            <td class="tdclass">
                                @php
                                if($planogram->photo_after_stocking_shelf!=null)
                                {
                                    $imagePath = asset('storage/' . $planogram->photo_after_stocking_shelf);
                                    echo "<img width='100' src='$imagePath' onclick='displayFullScreenImage(\"$imagePath\")' />";
                                }
                                else {
                                    echo "N/A";
                                }
                                    
                                @endphp     
                            </td>
                            <td class="tdclass">{{$planogram->action}}</td>
                            <td class="tdclass">{{$planogram->companyUser->user->name}}</td>

                        </tr>
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
    function displayFullScreenImage(imagePath) {
        // Create a modal element
        var modal = document.createElement('div');
        modal.className = 'imgmodal';
        modal.innerHTML = "<span class='close' onclick='closeFullScreenImage()'>&times;</span><img src='" + imagePath + "' class='imgmodal-content'>";
        document.body.appendChild(modal);

        // Show the modal
        modal.style.display = 'block';

        // Close the modal if clicked outside the image
        window.onclick = function(event) {
            if (event.target == modal) {
                closeFullScreenImage();
            }
        }
    }

    function closeFullScreenImage() {
        // Close the modal
        var modal = document.querySelector('.imgmodal');
        if (modal) {
            modal.style.display = 'none';
            modal.remove();
        }
    }

</script>


<script>
    var startDate= 0;
    var endDate = 0;
    var allStores = {!! json_encode($storesArr) !!};
    var allUniqueLocations = {!! json_encode($locationArr) !!};

    var labels = [];

</script>

<script src="{{ asset('assets/js/planogramComplianceTrackerDataTable.js') }}"></script>

{{-- @vite(['resources/js/chart.js']) --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#period-search", {
            dateFormat: "M d, Y",
            altFormat: "F j, Y",
            mode: "range",
            });
    });

    
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
        console.log(csvContent);
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', 'Planogram_compliance_Table.csv');
        document.body.appendChild(link);
        link.click();
        
    }
    document.getElementById('downloadButton').addEventListener('click', () => {
        const timeSheetTable = document.getElementById('planogramComplianceTrackerDatatable');
        downloadTable(timeSheetTable);
    });

</script>
@endsection
