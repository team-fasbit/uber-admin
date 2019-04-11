@extends('layouts.admin')

@section('title', tr('view_manager'))

@section('content-header', tr('view_manager'))

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li><a href="{{route('admin.managers')}}"><i class="fa fa-user"></i> {{tr('managers')}}</a></li>
    <li class="active"><i class="fa fa-user"></i> {{tr('view_manager')}}</li>
@endsection

@section('content')


@include('notification.notify')

<div class="panel">

    <div class="panel-body">
        <div class="col-md-12">
          <div class="col-md-6 col-md-offset-3">

    <div class="box box-widget widget-user-2 user_details_outer">

        <div class="widget-user-header bg-gray">
            <div class="widget-user-image">
                <img src="{{$corporate->picture ? $corporate->picture : asset('user_default.png')}}" class="avatar avatar-xlg img-circle" alt="">
            </div>
            <h3 class="widget-user-username">{{$corporate->name}} </h3>
        </div>

        <div class="box-footer no-padding">
        <dl class="dl-horizontal corporate-detail">

                          <dt>{{ tr('full_name') }} :</dt>
                          <dd>{{$corporate->name}}</dd>

                          <dt>{{ tr('email') }} :</dt>
                          <dd>{{$corporate->email}}</dd>

                          <dt>{{ tr('phone') }} :</dt>
                          <dd>{{$corporate->mobile}}</dd>

                          <dt>{{ tr('gender') }} :</dt>
                          <dd>{{$corporate->gender}}</dd>

                          <dt>{{ tr('available') }} :</dt>
                          <dd>
                            @if($corporate->is_available==1)
                                <span  class="label label-success"><b>{{ tr('yes') }}</b></span>
                            @else
                                <span class="label label-warning"><b>{{ tr('n_a') }}</b> </span>
                            @endif
                           </dd>

                          <dt>{{ tr('approved') }} :</dt>
                          <dd>
                            @if($corporate->is_approved==1)
                                <span class="label label-success"><b>{{ tr('approved') }}</b></span>
                            @else <span class="label label-warning"><b>{{ tr('unapproved') }}</b></span>
                            @endif
                          </dd>

                        </dl>
           <!--  <ul class="nav nav-stacked">
                <li><a href="javascript:void(0);">Full Name <span class="pull-right">Sudharsan Nishu</span></a></li>
                <li><a href="javascript:void(0);">Email <span class="pull-right">sudharsan12636@gmail.com</span></a></li>
                <li><a href="javascript:void(0);">Phone <span class="pull-right">+376123467890</span></a></li>
                <li><a href="javascript:void(0);">Gender <span class="pull-right">male</span></a></li>
                <li>
                <a href="javascript:void(0);">Average Rating 
                  <span class="pull-right">  
                    <ul class="text-white list-style-none mb0">
                        <li class="fa fa-star text-warning"></li>
                        <li class="fa fa-star text-warning"></li>
                        <li class="fa fa-star text-warning"></li>
                        <li class="fa fa-star text-warning"></li>
                        <li class="fa fa-star text-warning"></li>
                    </ul>
                        </span>
                    </a>
                </li>
            </ul> -->
        </div>
    
    </div>

</div>
           

<!--               <div class="col-md-8">

                <dl class="dl-horizontal corporate-detail">
                  <dt>Full Name :</dt>
                  <dd>{{$corporate->first_name}} {{$corporate->last_name}}</dd>

                  <dt>Email :</dt>
                  <dd>{{$corporate->email}}</dd>

                  <dt>Mobile :</dt>
                  <dd>{{$corporate->mobile}}</dd>

                  <dt>Gender :</dt>
                  <dd>{{$corporate->gender}}</dd>

                  <dt>Address :</dt>
                  <dd>{{$corporate->address}}</dd>


                  <dt>Available :</dt>
                  <dd>
                    @if($corporate->is_available==1)
                        <span  class="label label-success"><b>Yes</b></span>
                    @else
                        <span class="label label-warning"><b>N/A</b> </span>
                    @endif
                   </dd>

                  <dt>Approved :</dt>
                  <dd>
                    @if($corporate->is_approved==1)
                        <span class="label label-success"><b>Approved</b></span>
                    @else <span class="label label-warning"><b>Unapproved</b></span>
                    @endif
                  </dd>

                </dl>


            </div>

            <div class="col-md-4">
                <img style="width: 100%;" src="{{$corporate->picture ? $corporate->picture : asset('logo.png')}}">
            </div> -->

            </div>
        </div>
    </div>
</div>

@endsection
