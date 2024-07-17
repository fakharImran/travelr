<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" user-scalable="no">
    <link rel="icon" href="{{asset('assets/images/logo.png')}}" type="image/x-icon">

    <!-- StyleSheet -->
    {{-- <link href="{{asset('assets/css/bootstrap.min_.css')}}" rel="stylesheet"> --}}
    {{-- <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"> --}}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- ICons --}}
    <link rel="stylesheet" href="{{asset('assets/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @yield("top_links")


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">

</head>
<body>
    <div id="app">
        @if(isset($pageConfigs))
            @guest
                <main class="">
                    @yield('content')
                </main>
            @else
                <div class="site-wrapper">
                    {{-- <div class="main_header"> --}}
                        {{-- <div class="container-fluid"> --}}
                            <div class="row" style="width: 100%; margin:0px;">
                                <div class="logo_image">
                                    <img src="{{asset('assets/images/logo.png')}}">
                                </div>
                                <div class="col-md-2 col-0 p-0" id="left-side">
                                    <div style="visibility: hidden;">
                                        <img src="{{asset('assets/images/logo.png')}}">
                                    </div>
                                    @include('layouts.sidebar')
                                </div>
                                <div class=" col-md-10 col-12 p-0 " id="right-side" >
                                    @include('layouts.header')
                                    <main class="py-4">
                                        @yield('content')
                                    </main>
                                </div>
                            </div>
                        {{-- </div> --}}
                    {{-- </div> --}}
                </div>
            @endguest
        @else
            <main class="">
                @yield('content')
            </main>
        @endif
    </div>

{{-- <script src="assets/js/jquery.js"></script> --}}
{{-- <script src="assets/js/bootstrap.min_.js"></script> --}}
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const rightside = document.getElementById('right-side');
        const leftside = document.getElementById('left-side');
        const navbutton = document.getElementById('toggleButton');
        
        if (sidebar.classList.contains('half-width')) {
            sidebar.classList.remove('half-width');
            sidebar.classList.add('full-width');
            rightside.style.width = "100%";
            leftside.style.width = "0%";
            navbutton.style.marginLeft = "10%";
        } else {
            sidebar.classList.remove('full-width');
            sidebar.classList.add('half-width');
            rightside.style.width = "87%";
            leftside.style.width = "13%";
            navbutton.style.marginLeft = "0px";
        }
    }

    document.getElementById('toggleButton').addEventListener('click', toggleSidebar);
</script>
@yield("bottom_links")
</body>
</html>
