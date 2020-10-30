@extends('layouts.app')

@section('content')
<div class="limiter">
	<div class="container-login100" style="background-image:url({{url('login_design/images/background2.jpg')}})">
		<div class="wrap-login100 p-t-190 p-b-30">
			<form class="login100-form validate-form"  method="POST" action="{{ route('login') }}" onsubmit='show()'>
				{{ csrf_field() }}
				<div class="login100-form-avatar">
					<img src="{{URL::asset('/images/front-logo.png')}}" alt="AVATAR">
				</div>
				
				<span class="login100-form-title p-t-20 p-b-45">
					{{ config('app.name', 'Laravel') }}
				</span>
				
				<div class="wrap-input100 validate-input m-b-10" data-validate = "Email is required">
					<input class="input100" type="email" name="email" placeholder="Email" required>
					<span class="focus-input100"></span>
					<span class="symbol-input100">
						<i class="fa fa-user"></i>
					</span>
				</div>
				
				<div class="wrap-input100 validate-input m-b-10" data-validate = "Password is required" >
					<input class="input100" type="password" name="password" placeholder="Password" required>
					<span class="focus-input100"></span>
					<span class="symbol-input100">
						<i class="fa fa-lock"></i>
					</span>
				</div>
				
				<div class="container-login100-form-btn p-t-10">
					<button class="login100-form-btn">
						Login
					</button>
				</div>
				@if($errors->any())
					<div class="text-center w-full p-t-5">
						<span style="color:red;">
							<strong>{{$errors->first()}}</strong>
						</span>
					</div>
				@endif
				{{-- <div class="text-center w-full p-t-25 p-b-230">
					<a  href="{{ route('password.request') }}" onclick='show()' class="txt1" style='color:white;'>
						Forgot Password?
					</a>
				</div> --}}
				
			</form>
		</div>
	</div>
</div>


@endsection
