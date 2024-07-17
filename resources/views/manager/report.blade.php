<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" user-scalable="no">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.14/xlsx.full.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>


    <!-- StyleSheet -->
    {{-- <link href="{{asset('assets/css/bootstrap.min_.css')}}" rel="stylesheet"> --}}
    {{-- <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> --}}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- ICons --}}
    <link rel="stylesheet" href="{{ asset('assets/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">


    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>

    {{-- <script src="{{ asset('js/Chart.min.js') }}"></script> --}}

    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>
 

<div class="admin_logo ">
    <div class="row">
        <div class="col-8">
            <img class="img-fluid" src="{{ asset('assets/images/logo.png') }}" style="max-width: 80%;">
        </div>
        <div class="col-4 mt-50" style="margin-top: 100px">
            <label for="print"
                style="color: #000;
                                    font-family: Inter;
                                    font-size: 18px;
                                    font-style: normal;
                                    font-weight: 400;
                                    line-height: 24px; ">Print
                PDF &nbsp; </label>
            <label for="print" style="font-size: 24px" onclick="window.print()"><i class="fa fa-print"
                    yle="font-size:36px" aria-hidden="true"></i></label>
        </div>
    </div>

</div>

<div class="row" style="padding-left: 50px">
    <div class="col-12">
        <label for="Date"><b>Date Of Report:</b> {{ $todayDate }}</label>
    </div>
    <div class="col-12 mt-20">
        <label for="Date"><b>Selected Report Period:</b> {{ $startDate }} to {{$endDate}}</label>
    </div>
    <div class="col-12 mt-20">
        <label for="Company"><b>Company:</b> {{ $company_name }}</label>
    </div>
    <div class="col-12 mt-50" style="margin-top: 50px">
    </div>

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
    
        {{--start business overview Search --}}
    <div class="container business-overview">
        @include('manager.downloadReportComponents.businessSummary')
    </div>
        {{--end business overview Summary --}}



    <div class="col-12 mt-50" style="margin-top: 50px">
        <h2
            style="color: #2297C3;

        font-family: Inter;
        font-size: 35px;
        font-style: normal;
        font-weight: 700;
        line-height: 32px;">
            Category Summary Needs to be done</h2>
    </div>
    <div class="col-12 mt-50" style="margin-top: 50px">
        <h2
            style="color: #2297C3;

        font-family: Inter;
        font-size: 35px;
        font-style: normal;
        font-weight: 700;
        line-height: 32px;">
            Out Of Stock Summary</h2>
        {{-- @include('manager.outOfStock') --}}
    </div>
    <div class="col-12 mt-50" style="margin-top: 50px">
        <h2
            style="color: #2297C3;

        font-family: Inter;
        font-size: 35px;
        font-style: normal;
        font-weight: 700;
        line-height: 32px;">
            Sales Key Performance Indicators Needs to be done</h2>
        {{-- @include('manager.outOfStock') --}}
    </div>
    <div class="col-12 mt-50" style="margin-top: 50px">
        <h2
            style="color: #2297C3;

        font-family: Inter;
        font-size: 35px;
        font-style: normal;
        font-weight: 700;
        line-height: 32px;">
            Price Audit Summary </h2>
        {{-- @include('manager.priceAuditData') --}}
    </div>
    <div class="col-12 mt-50" style="margin-top: 50px">
        <h2
            style="color: #2297C3;

        font-family: Inter;
        font-size: 35px;
        font-style: normal;
        font-weight: 700;
        line-height: 32px;">
            Planogram Compliance Tracker Summary </h2>
        {{-- @include('manager.planogramComplianceTracker') --}}
    </div>
    <div class="col-12 mt-50" style="margin-top: 50px">
        <h2
            style="color: #2297C3;

        font-family: Inter;
        font-size: 35px;
        font-style: normal;
        font-weight: 700;
        line-height: 32px;">
            Marketing Activity Summary </h2>
        {{-- @include('manager.marketingActivity') --}}
    </div>
    <div class="col-12 mt-50" style="margin-top: 50px">
        <h2
            style="color: #2297C3;

        font-family: Inter;
        font-size: 35px;
        font-style: normal;
        font-weight: 700;
        line-height: 32px;">
            Opportunities / Threats </h2>
        {{-- @include('manager.opportunities') --}}
    </div>
</div>

</body>
</html>