<nav class="navbar navbar-expand-md navbar-dark navbar-color " style="background-color: #1892C0;">
   
   <style>
    body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 50%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

   </style>
   
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{-- {{ config('app.name', 'Laravel') }} --}}
            @yield('title')
            {{-- {{ __('Dashboard') }} --}}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                {{-- <li class="nav-item">
                    <a class="nav-link" href="#">Business Overview</a>
                </li> --}}
            </ul>
            <ul class="navbar-nav " style="    padding-left: 360px;">
                <a id="navbarDropdown" class="nav-link float-end" href="#" role="button"  aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ "Download Report" }}
                </a>
            </ul>
                <!-- The Modal -->
                <div id="dateModal" class="modal">

                <!-- Modal content -->
                <div class="modal-content">
                    <form action="{{route('generate-report')}}">
                        <span class="close">&times;</span>
                        <p>Please Select Date Range for generating Report</p>
                        <div class="form-group">
                            <label for="bs-period-search" class="form-label filter period filter-search">Period</label>
                            <input type="text" id="bs-period-search" name="date_range" value="Date Range" class=" form-control filter">
                            <i class="fas fa-times-circle clear-icon" id="bs-clearDate"></i>
        
                        </div>
                        <button type="submit" class="btn btn-success p-10">
                            Submit
                        </button>
                    </form>
                </div>

                </div>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
    <script>
        // Get the modal
var modal = document.getElementById("dateModal");

// Get the button that opens the modal
var btn = document.getElementById("navbarDropdown");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#bs-period-search", {
            dateFormat: "M d, Y",
            altFormat: "F j, Y",
            mode: "range",
        });
    });
</script>
</nav>