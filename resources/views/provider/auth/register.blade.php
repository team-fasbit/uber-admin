@extends('layouts.provider.focused')

@section('content')

<style>
	.alert {
    margin: 20px auto;
    max-width: 450px;
    border: none;
    box-shadow: 0px 0px 2px 0px rgba(0,0,0,0.5);
}
.login_outer {
       display: block;
    width: 100%;
    height: 100%;
    position: relative;
    background: #fff;
    padding: 20px;
    border-radius: 4px;
}
.login-box, .register-box {
    max-width: 500px !important;
    margin: auto !important;
}
.login_outer .inner{
	display: block;
}
.login_outer .inner h3{
	    margin: 0 0 1em 0;
}
.login_outer .inner p.text-center{
	margin: 10px 0 0;
}
</style>
<?php 
use App\Corporate;
?>

<div class="login_outer">

	<!-- @include('notification.notify')
 -->
	<div class="middle">

		<div class="inner">

		  <h3 class="text-center">Signup With..</h3>

			<!-- <span class="social_login">

				@if(config('services.facebook.client_id') && config('services.facebook.client_secret'))

                    <form class="" role="form" method="POST" action="{{ route('SocialLogin') }}">
                        <input type="hidden" value="facebook" name="provider" id="provider">

                        <button type="submit" class="btn fb_btn">
                            <i class="fa fa-facebook fa-2x"></i>
                        </button>
                    </form>

                @endif

                @if(config('services.google.client_id') && config('services.google.client_secret'))
 -->
                  <!--   <form class="" role="form" method="POST" action="{{ route('SocialLogin') }}">
                        <input type="hidden" value="google" name="provider" id="provider">

                        <button type="submit" class="btn gp_btn">
                            <i class="fa fa-google-plus fa-2x"></i>
                        </button>
                    </form>
 -->
<!--                 @endif

			</span>

			<div id="header">
			   <h3>Or</h3>
			</div> -->
			
			<form class="" action="{{route('provider.register.post')}}" method="POST">

				<div class="form-group">
					<!-- label class="col-sm-2 control-label" style="color:white;">First Name</label> -->
					<input type="text" name="first_name" class="form-control" placeholder="First Name.." required />
				</div>

				<div class="form-group">
					<!-- <label class="col-sm-2 control-label" style="color:white;">Last Name</label> -->
					<input type="text" name="last_name" class="form-control" placeholder="Last Name.." required />
				</div>

				<?php $corporates = Corporate::all(); ?>
				<div class="form-group">
                      <!-- <label class="col-sm-2 control-label" style="color:white;">Assign under a corporate?</label> -->
                      <div>
                        <select name="corporate" required class="form-control">
                            <option value="select service type">{{ tr('select_corporate') }} </option>
                            @foreach($corporates as $corporate)
                            @if(isset($provider->corporate_id))
                              @if($provider_type == $corporate->id)
                              <option value="{{$corporate->id}}" selected="true">{{$corporate->name}}</option>
                              @else
                              <option value="{{$corporate->id}}">{{$corporate->name}}</option>
                              @endif
                            @else
                            <option value="{{$corporate->id}}">{{$corporate->name}}</option>
                            @endif
                            @endforeach
                        </select>
                        
                      </div>
                    </div>

				<div class="form-group">
					<!-- <label class="col-sm-2 control-label" style="color:white;">Mobile No</label> -->
					<input type="text" name="mobile" class="form-control" placeholder="Mobile No.." required />
				</div>

				<div class="form-group">
					<!-- <label class="col-sm-2 control-label" style="color:white;">Email Id</label> -->
					<input type="email" name="email" class="form-control" placeholder="Email.." required />
				</div>

				<div class="form-group">
					<!-- <label class="col-sm-2 control-label" style="color:white;">Password</label> -->
					<input type="password" name="password" class="form-control" placeholder="Password.." required />
				</div>

				<div class="form-group">
					<!-- <label class="col-sm-2 control-label" style="color:white;">Confirm Password</label> -->
					<input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password.." required />
				</div>

				<input type="hidden" name="register_by" value="provider"/>

				<!-- <a href="#otpmodal" data-toggle="modal"> -->
					<button type="submit" class="btn btn-primary btn-block">SIGN UP</button>
				<!-- </a> -->
			</form>
			<p class="text-center">Already have an account? <a href="{{route('provider.login')}}">Login</a></p>
		</div>
	</div>

</div>
<!-- Modal -->
<div class="modal fade" id="otpmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Enter OTP to proceed</h4>
      </div>
      <div class="modal-body" style="padding-top:0;">
      	<div class="alert alert-info" role="alert" style="margin:10px auto;">We resend your OTP.Please check..</div>
      	<div class="alert alert-danger" role="alert" style="margin:10px auto;">Oops!..Please Enter valid OTP</div>
        <div class="form-group">
        	<label>Enter OTP here</label>
        	<input type="number" class="form-control" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">submit</button>
        <button type="button" class="btn btn-primary">Resend OTP</button>
      </div>
    </div>
  </div>
</div>
@endsection 	

@section('scripts')

@endsection
