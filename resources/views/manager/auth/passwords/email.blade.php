@extends('layouts.manager.focused')

@section('title', 'Reset Password')

@section('content')

    <div class="login-box-body" >
@include('notification.notify')
        <form class="form-layout" role="form" method="POST" action="{{ url('/manager/password/email') }}">
            {{ csrf_field() }}

            <div class="login-logo">
               <a href="{{route('manager.login')}}"><b>{{Setting::get('site_name')}}</b></a>
            </div>

            <p class="text-center mb25">Enter a email address to reset your password.</p></br>

            <div class="form-inputs">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" class="form-control" name="email" value="@if(session('email')!== ""){{ session('email') }}@else{{ old('email') }}@endif" placeholder="E-Mail Address">

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <div class="text-center">
                        <button class=" btn btn-warning btn-block mb15" type="submit">
                            <i class="fa fa-btn fa-envelope"></i> Reset
                        </button>

                         <a href="{{route('admin.dashboard')}}" class=" btn btn-info btn-block mb15">
                            <i class="fa fa-btn fa-user"></i> Login
                        </a>
                    </div>
                </div>

            </div>
        </form>

    </div>

@endsection
