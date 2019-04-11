@extends('layouts.corporate')

@section('title', tr('view_provider'))

@section('content-header', tr('view_provider'))

@section('breadcrumb')
    <li><a href="{{route('corporate.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li><a href="{{route('corporate.providers')}}"><i class="fa fa-user"></i> {{tr('providers')}}</a></li>
    <li class="active"><i class="fa fa-user"></i> {{tr('view_provider')}}</li>
@endsection

@section('content')


@include('notification.notify')

<div class="panel">

    <div class="panel-body">
        <div class="col-md-12">
            <div class="row">
                <div class="widget bg-white no-padding prov-prof">
                    <a href="javascript:;" class="block text-center relative p15">
                        <img src="{{$provider->picture ? $provider->picture : asset('user_default.png')}}" class="avatar avatar-xlg img-circle" alt="">
                    </a>
                    <div class="widget mb0 text-center no-radius">
                        <dl class="dl-horizontal provider-detail">

                          <dt>{{ tr('full_name') }} :</dt>
                          <dd>{{$provider->first_name}} {{$provider->last_name}}</dd>

                          <dt>{{ tr('email') }} :</dt>
                          <dd>{{$provider->email}}</dd>

                          <dt>{{ tr('phone') }} :</dt>
                          <dd>{{$provider->mobile}}</dd>

                          <dt>{{ tr('gender') }} :</dt>
                          <dd>{{$provider->gender}}</dd>

                          <dt>{{ tr('service_type') }} :</dt>
                          <dd>{{$service}}</dd>

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

                          <dt>{{ tr('available') }} :</dt>
                          <dd>
                            @if($provider->is_available==1)
                                <span  class="label label-success"><b>{{ tr('yes') }}</b></span>
                            @else
                                <span class="label label-warning"><b>{{ tr('n_a') }}</b> </span>
                            @endif
                           </dd>

                          <dt>{{ tr('approved') }} :</dt>
                          <dd>
                            @if($provider->is_approved==1)
                                <span class="label label-success"><b>{{ tr('approved') }}</b></span>
                            @else <span class="label label-warning"><b>{{ tr('unapproved') }}</b></span>
                            @endif
                          </dd>

                        </dl>
                    </div>
                </div>

<!--               <div class="col-md-8">

                <dl class="dl-horizontal provider-detail">
                  <dt>Full Name :</dt>
                  <dd>{{$provider->first_name}} {{$provider->last_name}}</dd>

                  <dt>Email :</dt>
                  <dd>{{$provider->email}}</dd>

                  <dt>Mobile :</dt>
                  <dd>{{$provider->mobile}}</dd>

                  <dt>Gender :</dt>
                  <dd>{{$provider->gender}}</dd>

                  <dt>Address :</dt>
                  <dd>{{$provider->address}}</dd>

                  <dt>Service Type :</dt>
                  <dd>{{$service}}</dd>

                  <dt>Available :</dt>
                  <dd>
                    @if($provider->is_available==1)
                        <span  class="label label-success"><b>Yes</b></span>
                    @else
                        <span class="label label-warning"><b>N/A</b> </span>
                    @endif
                   </dd>

                  <dt>Approved :</dt>
                  <dd>
                    @if($provider->is_approved==1)
                        <span class="label label-success"><b>Approved</b></span>
                    @else <span class="label label-warning"><b>Unapproved</b></span>
                    @endif
                  </dd>

                </dl>


            </div>

            <div class="col-md-4">
                <img style="width: 100%;" src="{{$provider->picture ? $provider->picture : asset('logo.png')}}">
            </div> -->

            </div>
        </div>
    </div>
</div>

@endsection
