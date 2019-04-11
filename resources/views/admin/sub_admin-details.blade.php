@extends('layouts.admin')

@section('title', tr('view_sub_admin'))

@section('content-header', tr('view_sub_admin'))

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <!--@if(isset($name))-->
      <li><a href="{{route('admin.sub_admins')}}"><i class="fa fa-user"></i> {{tr('sub_admins')}}</a></li>
    <!--@else-->
      <!--<li><a href="{{--route('admin.usermapview')--}}"><i class="fa fa-user"></i> {{tr('user_map_view')}}</a></li>-->
    <!--@endif-->
    <li class="active"><i class="fa fa-user"></i> {{tr('view_sub_admin')}}</li>
@endsection

@section('content')

@include('notification.notify')

<div class="col-md-6 col-md-offset-3">

    <div class="box box-widget widget-user-2 user_details_outer">

        <div class="widget-user-header bg-gray">
            <div class="widget-user-image">
                <img class="img-circle" src="{{$user->picture ? $user->picture : asset('user_default.png')}}" alt="User Avatar">
            </div>
            <h3 class="widget-user-username">{{$user->name}}</h3>
        </div>

        <div class="box-footer no-padding">
            <ul class="nav nav-stacked">
                <li><a href="javascript:void(0);">{{ tr('full_name') }} <span class="pull-right">{{$user->name}}</span></a></li>
                <li><a href="javascript:void(0);">{{ tr('email') }} <span class="pull-right">{{$user->email}}</span></a></li>
                <li><a href="javascript:void(0);">{{ tr('phone') }} <span class="pull-right">{{$user->mobile}}</span></a></li>
                <li><a href="javascript:void(0);">{{ tr('gender') }} <span class="pull-right">{{$user->gender}}</span></a></li>
                
            </ul>
        </div>
    
    </div>

</div>

@endsection
