<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <link href="{{ asset('/datatable/jquery.dataTables.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('/datatable/fixedColumns.dataTables.min.css')}}" rel="stylesheet" />
    <link rel="icon" type="image/png" href="{{ asset('/images/logo.ico')}}"/>
    <link href="{{ asset('/body/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('/body/css/paper-dashboard.css?v=2.0.0')}}" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    {{-- <link href="{{ asset('/body/demo/demo.css')}}../assets/" rel="stylesheet" /> --}}
    {{-- <script type="text/javascript" src="{{ asset('/js/app.js')}}"></script> --}}
    <!-- Styles -->
    <link href="{{ asset('/body/demo/demo.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id = "myDiv" style="display:none;" class="loader"></div>
    <div id="app">
        <div class="wrapper ">
            <div class="sidebar" data-color="white" data-active-color="danger">
          
                <div class="logo">
                    <a href="" class="simple-text logo-mini">
                        <div class="logo-image-small">
                            <img class="avatar border-gray" src="{{URL::asset('/images/front-logo.png')}}">
                        </div>
                    </a>
                    <a href="" class="simple-text logo-normal">
                        {{ config('app.name', 'Laravel') }}
                        
                    </a>
                </div>
                <div class="sidebar-wrapper">
                    <ul class="nav">
                     
                        @php
                        if(auth()->user()->team_id() != null)
                        {
                            $roles = auth()->user()->team_id()->role;
                            $roles_array = json_decode($roles);
                        }
                        @endphp
                        @if($roles_array != null)
                        @if((in_array(1,$roles_array)))
                        <li @if($header == "Summary Issues") class="active" @endif>
                            <a href="{{ url('/summary-issues') }}" onclick='show()'>
                                <i class="nc-icon nc-bank"></i>
                                <p>Summary Issues</p>
                            </a>
                        </li>
                        @endif
                        <li @if($header == "Dashboard") class="active" @endif>
                            <a href="{{ url('/') }}" onclick='show()'>
                                <i class="nc-icon nc-bank"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        @if((in_array(5,$roles_array)) )
                        <li  @if($header == "Issues") class="active" @endif>
                            <a href="{{ url('/issue') }}" onclick='show()'>
                                <i class="nc-icon nc-zoom-split"></i>
                                <p>Issues</p>
                            </a>
                        </li>
                        @endif
                        @if((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(4,$roles_array)) )
                        <li  @if($header == "Pending Issues") class="active" @endif>
                            <a href="{{ url('/pending-issue') }}" onclick='show()'>
                                <i class="nc-icon nc-single-copy-04"></i>
                                <p>Pending Issues</p>
                            </a>
                        </li>
                        @endif
                        @if((in_array(2,$roles_array)) || (in_array(3,$roles_array)))
                        <li  @if($header == "For Approval") class="active" @endif>
                            <a href="{{ url('/for-approval') }}" onclick='show()'>
                                <i class="nc-icon nc-check-2"></i>
                                <p>For Approval</p>
                            </a>
                        </li>
                        @endif
                        @if((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(4,$roles_array)) || (in_array(5,$roles_array))  )
                        <li  @if($header == "Action Plans") class="active" @endif>
                            <a href="{{ url('/action-plans') }}" onclick='show()'>
                                <i class="nc-icon nc-send"></i>
                                <p>Action Plans Not Due</p>
                            </a>
                        </li>
                        @endif
                        @if((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(4,$roles_array)) || (in_array(5,$roles_array))  )
                        <li  @if($header == "Action Plans Due") class="active" @endif>
                            <a href="{{ url('/action-plans-due') }}" onclick='show()'>
                                <i class="nc-icon nc-send"></i>
                                <p>Action Plans Due</p>
                            </a>
                        </li>
                        @endif
                        @if((in_array(5,$roles_array)))
                        <li  @if($header == "For Audit") class="active" @endif>
                            <a href="{{ url('/for-audit') }}" onclick='show()'>
                                <i class="nc-icon nc-paper"></i>
                                <p>Audit Verification</p>
                            </a>
                        </li>
                        <li  @if($header == "Closed Issues") class="active" @endif>
                            <a href="{{ url('/closed-issues') }}" onclick='show()'>
                                <i class="nc-icon nc-paper"></i>
                                <p>Closed Issues</p>
                            </a>
                        </li> 
                        <li  @if($header == "Action Plan Verified") class="active" @endif>
                            <a href="{{ url('/verified-action-plans') }}" onclick='show()'>
                                <i class="nc-icon nc-paper"></i>
                                <p>Closed Action Plans</p>
                            </a>
                        </li>
                        @endif
                        @if((in_array(2,$roles_array)) || (in_array(3,$roles_array)) || (in_array(4,$roles_array)) )
                        <li  @if($header == "Action Plan Verified") class="active" @endif>
                            <a href="{{ url('/verified-action-plans') }}" onclick='show()'>
                                <i class="nc-icon nc-paper"></i>
                                <p>Closed Action Plans</p>
                            </a>
                        </li>
                        <li  @if($header == "Returned Action Plans") class="active" @endif>
                            <a href="{{ url('/return-action-plans') }}" onclick='show()'>
                                <i class="nc-icon nc-paper"></i>
                                <p>Returned Action Plans</p>
                            </a>
                        </li>  
                        <li  @if($header == "Closed Issues") class="active" @endif>
                            <a href="{{ url('/closed-issues') }}" onclick='show()'>
                                <i class="nc-icon nc-paper"></i>
                                <p>Closed Issues</p>
                            </a>
                        </li> 
                        @endif
                        
                        <li  @if($header == "Potential Issues") class="active" @endif>
                            <a href="{{ url('/potential-issues') }}" onclick='show()'>
                                <i class="nc-icon nc-paper"></i>
                                <p>Potential Issues</p>
                            </a>
                        </li>
                        @if((in_array(1,$roles_array)))
                        <li @if($header == "Monitoring") class="active" @endif>
                            <a href="{{ url('/monitoring') }}" onclick='show()'> 
                                <i class="nc-icon nc-paper"></i>
                                <p>Action Plan Monitoring</p>
                            </a>
                        </li>
                        @endif
                        <hr>
                        @if((in_array(1,$roles_array)))
                        <li @if($header == "Users") class="active" @endif>
                            <a href="{{ url('/users') }}" onclick='show()'> 
                                <i class="nc-icon nc-single-02"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        @endif
                        @if((in_array(1,$roles_array)))
                        <li @if($header == "Auditors") class="active" @endif>
                            <a href="{{ url('/auditors') }}" onclick='show()'> 
                                <i class="nc-icon nc-badge"></i>
                                <p>Auditors</p>
                            </a>
                        </li>
                        @endif
                        @if((in_array(1,$roles_array)))
                        <li @if($header == "Business Unit") class="active" @endif>
                            <a href="{{ url('/business-unit') }}" onclick='show()'> 
                                <i class="nc-icon nc-paper"></i>
                                <p>Business Units</p>
                            </a>
                        </li>
                        @endif
                      
                        @endif
                        
                    </ul>
                </div>
            </div>
            <div class="main-panel">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
                    <div class="container-fluid">
                        <div class="navbar-wrapper">
                            <div class="navbar-toggle">
                                <button type="button" class="navbar-toggler">
                                    <span class="navbar-toggler-bar bar1"></span>
                                    <span class="navbar-toggler-bar bar2"></span>
                                    <span class="navbar-toggler-bar bar3"></span>
                                </button>
                            </div>
                            <a class="navbar-brand" href="#">{{$header}}</a>
                        </div>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                            <span class="navbar-toggler-bar navbar-kebab"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-end" id="navigation">
                            <ul class="navbar-nav">
                                <li class="nav-item ">
                                    <a class="nav-link dropdown-toggle" href="#" id="account" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="nc-icon nc-single-02"></i>
                                        {{auth()->user()->employee_info()->first_name.' '.auth()->user()->employee_info()->last_name}}
                                        <p>
                                            <span class="d-lg-none d-md-block">Account</span>
                                        </p>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="account">
                                        <a class="dropdown-item" data-toggle="modal" data-target="#profile" data-toggle="profle">Change Password</a>
                                        <a class="dropdown-item"  href="{{ route('logout') }}"  onclick="logout(); show();">Logout</a>
                                    </div>
                                    <form id="logout-form"  action="{{ route('logout') }}"  method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                @include('change_password')
                @yield('content')
            </div>
        </div>
    </div>
 
<script src="{{ asset('/body/js/core/jquery.min.js')}}"></script>
<script src="{{ asset('/body/js/core/popper.min.js')}}"></script>
<script src="{{ asset('/body/js/core/bootstrap.min.js')}}"></script>
<script src="{{ asset('/body/js/plugins/perfect-scrollbar.jquery.min.js')}}"></script>
<!--  Google Maps Plugin    -->
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script> --}}
<!-- Chart JS -->
<script src="{{ asset('/body/js/plugins/chartjs.min.js')}}"></script>
<!--  Notifications Plugin    -->
<script src="{{ asset('/body/js/plugins/bootstrap-notify.js')}}"></script>
<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="{{ asset('/body/js/paper-dashboard.min.js?v=2.0.0')}}" type="text/javascript"></script>
<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
<script src="{{ asset('/body/demo/demo.js')}}"></script>
<script>
  $(document).ready(function() {
    // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
    demo.initChartsPages();
  });
</script>
</body>
</html>
