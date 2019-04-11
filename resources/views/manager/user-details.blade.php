@extends('layouts.admin')

@section('title', tr('view_user'))

@section('content-header', tr('view_user'))

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    @if(isset($name))
      <li><a href="{{route('admin.users')}}"><i class="fa fa-user"></i> {{tr('users')}}</a></li>
    @else
      <li><a href="{{route('admin.usermapview')}}"><i class="fa fa-user"></i> {{tr('user_map_view')}}</a></li>
    @endif
    <li class="active"><i class="fa fa-user"></i> {{tr('view_user')}}</li>
@endsection

@section('content')

@include('notification.notify')

<!-- <div class="panel">

    <div class="panel-body">
        <div class="col-md-12">
            <div class="row">
                <div class="widget bg-white no-padding prov-prof">
                    <a href="javascript:;" class="block text-center relative p15">
                        <img src="{{$user->picture ? $user->picture : asset('user_default.png')}}" class="avatar avatar-xlg img-circle" alt="user_img" width="100">
                    </a>
                    <div class="widget mb0 no-radius">
                        <dl class="dl-horizontal provider-detail">

                            <dt>{{ tr('full_name') }} :</dt>
                            <dd>{{$user->first_name}} {{$user->last_name}}</dd>

                            <dt>{{ tr('email') }} :</dt>
                            <dd>{{$user->email}}</dd>

                            <dt>{{ tr('phone') }} :</dt>
                            <dd>{{$user->mobile}}</dd>

                            <dt>{{ tr('gender') }} :</dt>
                            <dd>{{$user->gender}}</dd>

                            <dt>{{ tr('avg_rating') }} :</dt>
                            <dd>
                                @if($review > 0)
                                    <ul class="text-white list-style-none mb0">
                                    @for($i=0; $i<$review; $i++)
                                        <li class="fa fa-star text-warning"></li>
                                    @endfor
                                    </ul>
                                @else
                                    <span>N/A</span>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="col-md-6 col-md-offset-3">

    <div class="box box-widget widget-user-2 user_details_outer">

        <div class="widget-user-header bg-gray">
            <div class="widget-user-image">
                <img class="img-circle" src="{{$user->picture ? $user->picture : asset('user_default.png')}}" alt="User Avatar">
            </div>
            <h3 class="widget-user-username">Janaina Leal </h3>
        </div>

        <div class="box-footer no-padding">
            <ul class="nav nav-stacked">
                <li><a href="javascript:void(0);">{{ tr('full_name') }} <span class="pull-right">{{$user->first_name}} {{$user->last_name}}</span></a></li>
                <li><a href="javascript:void(0);">{{ tr('email') }} <span class="pull-right">{{$user->email}}</span></a></li>
                <li><a href="javascript:void(0);">{{ tr('phone') }} <span class="pull-right">{{$user->mobile}}</span></a></li>
                <li><a href="javascript:void(0);">{{ tr('gender') }} <span class="pull-right">{{$user->gender}}</span></a></li>
                <li>
                    <a href="javascript:void(0);">{{ tr('avg_rating') }} <span class="pull-right"> @if($review > 0)
                        <ul class="text-white list-style-none mb0">
                        @for($i=0; $i<$review; $i++)
                            <li class="fa fa-star text-warning"></li>
                        @endfor
                        </ul>
                        @else
                            <span>N/A</span>
                        @endif</span>
                    </a>
                </li>
            </ul>
        </div>
    
    </div>

</div>

@endsection
