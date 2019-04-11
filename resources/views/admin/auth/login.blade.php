@extends('layouts.admin.focused')

@section('title', 'Login')


@section('content')

    
    <div class="login-box-body" style="height:auto;">


        <form class="form-layout" role="form" method="POST" action="{{ url('/admin/login') }}">
            {{ csrf_field() }}

            <!--<div class="login-logo">
               <!-- <a href="{{route('admin.login')}}"><b>{{Setting::get('site_name')}}</b></a>
            </div>-->

            <p class="text-center mb30"></p>

            <div class="form-inputs">
                <div class="login-logo">
             <a href="{{route('admin.login')}}"><b> <img class="adm-log-logo" style="width:50%;height:auto" src="@if(Setting::get('site_logo')) {{Setting::get('site_logo')}} @else {{asset('/images/logo.png')}} @endif" /></b></a>
        </div>
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" class="form-control input-lg" name="email" placeholder="Email" value="">

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif

                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                    <input type="password" class="form-control input-lg" name="password" placeholder="Password" value=""    >

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif

                </div>


            </div>

            <div class="col-md-6 col-md-offset-3">
                <button class="btn btn_login_new btn-block mb15" type="submit">
                    <h5><span><i class="fa fa-btn fa-sign-in"></i> Login</span></h5>
                </button>
            </div>

            <div class="form-group">
                    <a style="margin-left:100px" class="btn btn-link" href="{{ url('/admin/password/reset') }}">Reset Password</a>
            </div>

        </form>

    </div>



@endsection

