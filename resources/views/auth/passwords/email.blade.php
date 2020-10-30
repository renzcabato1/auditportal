@extends('layouts.app')

@section('content')
<div class="limiter">
    <div class="container-login100" style="background-image:url({{url('login_design/images/background2.jpg')}})">
        <div class="wrap-login100 p-t-190 p-b-30">
            <form class="login100-form validate-form" method="POST" action="{{ route('password.email') }}" onsubmit="show()">
                {{ csrf_field() }}
                <div class="login100-form-avatar">
                    <img src="{{URL::asset('/images/front-logo.png')}}" alt="AVATAR">
                </div>
                
                <span class="login100-form-title p-t-20 p-b-45">
                    Reset Password
                </span>
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif
                
                <div class="wrap-input100 validate-input m-b-10" data-validate = "Email is required">
                    <input  id="email" type="email"class="input100" name="email" value="{{ old('email') }}" placeholder="Please Enter Your Email" required autofocus>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-user"></i>
                    </span>
                </div>
                
                @if ($errors->has('email'))
                <div class="text-center w-full p-t-5">
                    <strong style='color:red;'>{{ $errors->first('email') }}</strong>
                </div>
                @endif
                <div class="container-login100-form-btn p-t-10  p-b-230">
                    <button class="login100-form-btn p-b-30 m-b-5" >
                        Send Password Reset Link
                    </button>
                    <button class="login100-form-btn p-t-10 p-b-50" >
                        <a style='color:white' href="{{  url('/login') }}"  onclick='show()'>Back to Login Page</a>
                    </button>
                    
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
