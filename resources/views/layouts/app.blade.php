<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Fonts -->
    <link rel="icon" type="image/png" href="{{ asset('/images/logo.ico')}}"/>
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/login_design/vendor/bootstrap/css/bootstrap.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/login_design/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/login_design/fonts/Linearicons-Free-v1.0.0/icon-font.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/login_design/vendor/animate/animate.css')}}">
    <!--===============================================================================================-->	
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('/login_design/vendor/css-hamburgers/hamburgers.min.css')}}"> --}}
    <!--===============================================================================================-->
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('/login_design/vendor/animsition/css/animsition.min.css')}}"> --}}
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/login_design/vendor/select2/select2.min.css')}}">
    <!--===============================================================================================-->	
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/login_design/css/util.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/login_design/css/main.css')}}">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{ asset('/images/3.gif')}}") 50% 50% no-repeat rgb(249,249,249) ;
            opacity: .8;
            background-size:200px 120px;
        }
    </style>
</head>
<body>
    <div id = "myDiv" style="display:none;" class="loader">
    </div>
    <div id="app">
        
        
        <main>
            @yield('content')
        </main>
    </div>
    <script type='text/javascript'>
        function show() {
            document.getElementById("myDiv").style.display="block";
        }
    </script>
    <!--===============================================================================================-->
    <script src="{{ asset('/login_design/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
    <!--===============================================================================================-->
    {{-- <script src="{{ asset('/login_design/vendor/animsition/js/animsition.min.js')}}"></script> --}}
    <!--===============================================================================================-->
    <script src="{{ asset('/login_design/vendor/bootstrap/js/popper.js')}}"></script>
    <script src="{{ asset('/login_design/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('/login_design/vendor/select2/select2.min.js')}}"></script>
    <!--===============================================================================================-->
    {{-- <script src="{{ asset('/login_design/vendor/jquery/jquery-3.2.1.min.js')}}"></script> --}}
    <!--===============================================================================================-->
    {{-- <script src="{{ asset('/login_design/vendor/countdowntime/countdowntime.js')}}"></script> --}}
    <!--===============================================================================================-->
    <script src="{{ asset('/login_design/js/main.js')}}"></script>
</body>
</html>
