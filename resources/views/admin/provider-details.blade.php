@extends('layouts.admin')

@section('title', tr('view_provider'))

@section('content-header', tr('view_provider'))

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li><a href="{{route('admin.providers')}}"><i class="fa fa-user"></i> {{tr('providers')}}</a></li>
    <li class="active"><i class="fa fa-user"></i> {{tr('view_provider')}}</li>
@endsection

@section('content')


@include('notification.notify')

<div class="col-md-6 col-md-offset-3">

    <div class="box box-widget widget-user-2 user_details_outer">

        <div class="widget-user-header bg-gray">
            <div class="widget-user-image">
                <img class="img-circle" src="{{$provider->picture ? $provider->picture : asset('user_default.png')}}" alt="User Avatar">
            </div>
            <h3 class="widget-user-username">{{$provider->first_name}} {{$provider->last_name}}</h3>
        </div>

        <div class="box-footer no-padding">
            <ul class="nav nav-stacked">
                <li>
                  <a href="javascript:void(0);">{{ tr('full_name') }} :<span class="pull-right">{{$provider->first_name}} {{$provider->last_name}}</span></a>
                </li>
                <li>
                  <a href="javascript:void(0);">{{ tr('email') }} :<span class="pull-right">{{$provider->email}}</span></a>
                </li>
                <li>
                  <a href="javascript:void(0);">{{ tr('phone') }} :<span class="pull-right">{{$provider->mobile}}</span></a>
                </li>
                 <li>
                  <a href="javascript:void(0);">{{ tr('gender') }} :<span class="pull-right">{{$provider->gender}}</span></a>
                </li>
                <li>
                  <a href="javascript:void(0);">{{ tr('service_type') }} :<span class="pull-right">{{$service}}</span></a>
                </li>
                <!--ff-->
                 <li>
                  <a href="javascript:void(0);">{{ tr('avg_rating') }} :<span class="pull-right">
                     @if($review > 0)
                                    <ul class="text-white list-style-none mb0">
                                    @for($i=0; $i<$review; $i++)
                                        <li class="fa fa-star text-warning"></li>
                                    @endfor
                                    </ul>
                                @else
                                    <span>N/A</span>
                                @endif
                  </span></a>
                </li>
                 <!--ff-->
                 <li>
                  <a href="javascript:void(0);">{{ tr('available') }} :<span class="pull-right">
                     @if($provider->is_available==1)
                                <span  class="label label-success"><b>{{ tr('yes') }}</b></span>
                            @else
                                <span class="label label-warning"><b>{{ tr('n_a') }}</b> </span>
                            @endif
                  </span></a>
                </li>
                 <!--ff-->
                  <li>
                  <a href="javascript:void(0);">{{ tr('approved') }} :<span class="pull-right">
                     @if($provider->is_approved==1)
                                <span class="label label-success"><b>{{ tr('approved') }}</b></span>
                            @else <span class="label label-warning"><b>{{ tr('unapproved') }}</b></span>
                            @endif
                  </span></a>
                </li>

            </ul>
        </div>

    </div>

</div>

        </div>

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

            <!--</div> -->


  <!--       </div>
    </div>
</div> -->

@endsection
