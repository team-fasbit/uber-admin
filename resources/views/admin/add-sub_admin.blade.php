@extends('layouts.admin')

@if(isset($name))
  @section('title', tr('edit_sub_admin'))
@else
  @section('title', tr('add_sub_admin'))
@endif

@if(isset($name))
  @section('content-header', 'Edit a sub-admin')
@else
  @section('content-header', 'Add a sub-admin')
@endif


@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li><a href="{{route('admin.sub_admins')}}"><i class="fa fa-users"></i> {{tr('sub_admins')}}</a></li>
    @if(isset($name))
      <li class="active">{{tr('edit_sub_admin')}}</li>
    @else
      <li class="active">{{tr('add_sub_admin')}}</li>
    @endif

@endsection

@section('content')

@include('notification.notify')

    <div class="row">

        <div class="col-md-12">

            <div class="box box-info" style="padding: 1.5em 0 6px;">
                <!-- <div class="box-header">
                    <div class="map_content"> -->
                      <!--<p class="lead para_mid">
                          Why should I Add a Passenger Manually? 
                      </p>
                       <p class="lead para_mid">
                         We hear this often. Often there are situations, where you might get a call from a passenger requesting for a taxi. In this case, you can get his details & manually add the passenger and send him a Taxi. This is called Manual Taxi Dispatch.
                       </p>
                       <p class="lead para_mid">
                       Once you type in his current address, and submit > The next screen would show you the list of drivers available in that location > So you can tag a Driver to the passenger and initiate the ride.
                       </p>
                       <p class="lead para_mid">
                          Never miss an opportunity - Ever!
                       </p>  -->
                    <!-- </div>
                </div> -->
              <form class="form-horizontal bordered-group" action="{{route('admin.save.sub_admin')}}" method="POST" enctype="multipart/form-data" role="form">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ tr('name') }}</label>
                  <div class="col-sm-8">
                    <input type="text" name="name" value="{{isset($user->name) ? $user->name : ''}}" required class="form-control">
                  </div>
                </div>
                <!--<div class="form-group">
                  <label class="col-sm-2 control-label">{{ tr('first_name') }}</label>
                  <div class="col-sm-8">
                    <input type="text" name="first_name" value="{{-- isset($user->first_name) ? $user->first_name : '' --}}" required class="form-control">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ tr('last_name') }}</label>
                  <div class="col-sm-8">
                    <input type="text" name="last_name" value="{{-- isset($user->last_name) ? $user->last_name : '' --}}" required class="form-control">
                  </div>
                </div>-->

                <input type="hidden" name="id" value="@if(isset($user)) {{$user->id}} @endif" />

                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ tr('gender') }}</label>

                  <div class="col-sm-8">
                    <div class="radio">
                      <label>
                        <input name="gender" @if(isset($user)) @if($user->gender == 'male') checked @endif @endif value="male" type="radio">{{ tr('male') }}</label>
                    </div>
                    <div class="radio">
                      <label>
                        <input type="radio"@if(isset($user)) @if($user->gender == 'female') checked @endif @endif name="gender" value="female">{{ tr('female') }}</label>
                    </div>
                    <div class="radio">
                      <label>
                        <input type="radio"@if(isset($user)) @if($user->gender == 'others') checked @endif @endif name="gender" value="others">{{ tr('others') }}</label>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ tr('email') }}</label>
                  <div class="col-sm-8">
                    <input type="email" name="email" value="{{isset($user->email) ? $user->email : ''}}" required class="form-control">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ tr('contact_num') }} </label>
                  <div class="col-sm-8">
                    <input type="text" name="mobile"  value="{{isset($user->mobile) ? $user->mobile : ''}}" required class="form-control">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ tr('password') }}</label>
                  <div class="col-sm-8">
                    <input type="text" name="password" value="" required class="form-control">
                  </div>
                </div>

                <!--<div class="form-group">
                  <label class="col-sm-2 control-label">Pickup Location</label>
                  <div class="col-sm-8">
                    <textarea name="address" required class="form-control" rows="3">{{ isset($user->address) ? $user->address : '' }}</textarea>
                  </div>
                </div>-->

                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ tr('profile_pic') }}</label>
                  <div class="col-sm-8">
                  @if(isset($user->picture))
                  <img class="add_ser_profile_pic" src="{{$user->picture}}">
                  @endif
                    <input name="picture" type="file">
                    <p class="help-block">{{ tr('upload_message') }}</p>
                  </div>
                </div>

                <div class="form-group">
                    <p class="col-sm-2 control-label lead"><b>Access to:</b></p>
                </div>

                <!--<div class="form-group">
                  <label class="col-sm-2 control-label">Dashboard</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                   <input type="checkbox" name="dashboard[]" style="    margin: 5px 10px;position:relative;top:2px;"value="3">{{tr('view')}}
                  </div>
                </div>-->

                <div class="form-group">
                  <label class="col-sm-2 control-label lead"><b>Stats on Map</b></label>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Booking Stats</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                     @php if(isset($user)) { $booking_stats = explode(' ', $user->booking_stats);  

                     if($booking_stats[0]!="")
                     {
                     @endphp
                     <input type="checkbox" name="booking_stats[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>{{ tr('view') }}
                     @php 
                   }else{
                   @endphp

                  <input type="checkbox" name="booking_stats[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">{{ tr('view') }}
                  @php }
                }else{
                  @endphp
                  <input type="checkbox" name="booking_stats[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">{{ tr('view') }}
                   @php }
                  @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Driver availability Stats</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                    @php if(isset($user)) { $driver_availability_stats = explode(' ', $user->driver_availability_stats);  

                     if($driver_availability_stats[0]!="")
                     {
                     @endphp
                  <input type="checkbox" name="driver_availability_stats[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>{{ tr('view') }}
                      @php 
                   }else{
                   @endphp
                  <input type="checkbox" name="driver_availability_stats[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">{{ tr('view') }}
                      @php 
                    }
                  }else
                  {
                    @endphp
                     <input type="checkbox" name="driver_availability_stats[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">{{ tr('view') }}
                 @php }
                 @endphp
                   
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Corporate's under you</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                    @php if(isset($user)) { $corporat = explode(',', $user->corporates);  

                     if(in_array(1,$corporat)!=0)
                     {
                     @endphp
                     
                   <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="1" checked>{{tr('add')}}
                   @php } else
                   {
                   @endphp
                   <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="1">{{tr('add')}}
                    @php
                  }  if(in_array(2,$corporat)!=0)
                     {
                    @endphp
                   <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="2" checked>{{tr('edit')}}
                   @php 
                    }else{
                    @endphp
                     <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="2" >{{tr('edit')}}
                     @php 
                    }
                    if(in_array(3,$corporat))
                     {
                    @endphp
                   <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="3" checked>View All Corporates
                   @php 
                    }else{
                    @endphp
                    <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="3">View All Corporates
                   @php   }
                    if(in_array(4,$corporat)!=0)
                     {
                    @endphp
                   <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="4" checked>{{tr('delete')}}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="4">{{tr('delete')}}
                    @php   }
                    if(in_array(10,$corporat)!=0)
                     {
                    @endphp
                   <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="10" checked>View Corporate Details
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="10">View Corporate Details
                    @php   }
                    if(in_array(11,$corporat)!=0)
                     {
                    @endphp
                   <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="11" checked>Reset Password
                   @php 
                    }else{
                    @endphp
                    <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="11">Reset Password
                     @php   }
                   }
                     @endphp

                     @php if(!isset($user)) { @endphp
                    <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="1">{{tr('add')}}
                   <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="2">{{tr('edit')}}
                   <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="3">View All Corporates
                   <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="4">{{tr('delete')}}
                   <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="10">View Corporate Details
                   <input type="checkbox" name="corporates[]" style="    margin: 5px 10px;position:relative;top:2px;"value="11">Reset Password
                  @php } @endphp
                  </div>
                </div>

                
                <div class="form-group">
                  <label class="col-sm-2 control-label">Call Center Management</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                     @php if(isset($user)) { $call_center_manager = explode(',', $user->call_center_managers);  

                     if(in_array(1,$call_center_manager)!=0)
                     {
                     @endphp
                     
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="1" checked>{{tr('add')}}
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{tr('add')}}
                    @php   }
                    if(in_array(2,$call_center_manager))
                     {
                    @endphp
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="2" checked>{{tr('edit')}}
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{tr('edit')}}
                   @php   }
                    if(in_array(3,$call_center_manager))
                     {
                    @endphp
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>view all call_center_managers
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all call_center_managers
                   @php   }
                    if(in_array(4,$call_center_manager))
                     {
                    @endphp
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="4" checked>{{tr('delete')}}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{tr('delete')}}
                   @php   }
                    if(in_array(10,$call_center_manager))
                     {
                    @endphp
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="10" checked>view call_center_manager details
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="10">view call_center_manager details
                   @php   }
                    if(in_array(11,$call_center_manager))
                     {
                    @endphp
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="11" checked>Reset Password
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="11">Reset Password
                   @php }
                 }
                   @endphp

                   @php if(!isset($user)) { @endphp
                    <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{tr('add')}}
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{tr('edit')}}
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all call_center_managers
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{tr('delete')}}
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="10">view call_center_manager details
                   <input type="checkbox" name="call_center_managers[]" style="margin: 5px 10px;position:relative;top:2px;" value="11">Reset Password
                  @php } @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Passenger Management</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                     @php if(isset($user)) { $use = explode(',', $user->users);  

                     if(in_array(1,$use)!=0)
                     {
                     @endphp
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="1" checked>{{tr('add')}}
                   @php 
                    }else{
                    @endphp
                     <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="1">{{tr('add')}}
                     @php   }
                    if(in_array(2,$use))
                     {
                    @endphp
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="2" checked>{{tr('edit')}}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="2">{{tr('edit')}}
                    @php   }
                    if(in_array(3,$use))
                     {
                    @endphp
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="3" checked>view all users
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="3">view all users
                   @php   }
                    if(in_array(4,$use))
                     {
                    @endphp
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="4" checked>{{tr('delete')}}
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="4">{{tr('delete')}}
                   @php   }
                    if(in_array(5,$use))
                     {
                    @endphp
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="5" checked>{{tr('view_history')}}
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="5">{{tr('view_history')}}
                    @php   }
                    if(in_array(10,$use))
                     {
                    @endphp
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="10" checked>view user details
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="10">view user details
                   @php   }
                    if(in_array(11,$use))
                     {
                    @endphp
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;" value="11" checked>Reset Password
                     @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;" value="11">Reset Password
                   @php } 
                 }
                   @endphp

                   @php if(!isset($user)) { @endphp
                    <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="1">{{tr('add')}}
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="2">{{tr('edit')}}
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="3">view all users
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="4">{{tr('delete')}}
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="5">{{tr('view_history')}}
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;"value="10">view user details
                   <input type="checkbox" name="users[]" style="    margin: 5px 10px;position:relative;top:2px;" value="11">Reset Password
                  @php } @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Driver Management</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                    @php if(isset($user)) { $provider = explode(',', $user->providers);  

                     if(in_array(1,$provider)!=0)
                     {
                     @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="1" checked>{{tr('add')}}
                     @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="1">{{tr('add')}}
                    @php   }
                    if(in_array(2,$provider))
                     {
                    @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="2" checked>{{ tr('edit') }}
                   @php 
                    }else{
                    @endphp
                    <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="2">{{ tr('edit') }}
                     @php   }
                    if(in_array(3,$provider))
                     {
                    @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="3" checked>view all drivers
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="3">view all drivers
                    @php   }
                    if(in_array(4,$provider))
                     {
                    @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="4" checked>{{tr('delete')}}
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="4">{{tr('delete')}}
                    @php   }
                    if(in_array(8,$provider))
                     {
                    @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="8" checked>{{ tr('approve') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="8">{{ tr('approve') }}
                   @php   }
                    if(in_array(9,$provider))
                     {
                    @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="9" checked>{{ tr('decline') }}
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="9">{{ tr('decline') }}
                    @php   }
                    if(in_array(5,$provider))
                     {
                    @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="5" checked>{{ tr('view_history') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="5">{{ tr('view_history') }}
                     @php   }
                    if(in_array(6,$provider))
                     {
                    @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="6" checked>{{ tr('view_docs') }}
                     @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="6">{{ tr('view_docs') }}
                    @php   }
                    if(in_array(11,$provider))
                     {
                    @endphp
                    <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;" value="11" checked>Reset Password
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;" value="11">Reset Password
                   @php }
                 }
                   @endphp

                   @php if(!isset($user)) { @endphp
                    <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="1">{{tr('add')}}
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="2">{{ tr('edit') }}
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="3">view all drivers
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="4">{{tr('delete')}}
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="8">{{ tr('approve') }}
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="9">{{ tr('decline') }}
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="5">{{ tr('view_history') }}
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;"value="6">{{ tr('view_docs') }}
                   <input type="checkbox" name="providers[]" style="margin: 5px 10px;position:relative;top:2px;" value="11">Reset Password
                  @php } @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Sub Admin Management</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                    @php if(isset($user)) { $sub_admin = explode(',', $user->sub_admins);  

                     if(in_array(1,$sub_admin)!=0)
                     {
                     @endphp
                   <input type="checkbox" name="sub_admins[]" style="    margin: 5px 10px;position:relative;top:2px;"value="1" checked>{{tr('add')}}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="sub_admins[]" style="    margin: 5px 10px;position:relative;top:2px;"value="1">{{tr('add')}}
                   @php   }
                    if(in_array(2,$sub_admin))
                     {
                    @endphp
                   <input type="checkbox" name="sub_admins[]" style="    margin: 5px 10px;position:relative;top:2px;"value="2" checked>{{tr('edit')}}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="sub_admins[]" style="    margin: 5px 10px;position:relative;top:2px;"value="2">{{tr('edit')}}
                    @php   }
                    if(in_array(3,$sub_admin))
                     {
                    @endphp
                   <input type="checkbox" name="sub_admins[]" style="    margin: 5px 10px;position:relative;top:2px;"value="3" checked>view all sub-admins
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="sub_admins[]" style="    margin: 5px 10px;position:relative;top:2px;"value="3">view all sub-admins
                    @php   }
                    if(in_array(4,$sub_admin))
                     {
                    @endphp
                   <input type="checkbox" name="sub_admins[]" style="    margin: 5px 10px;position:relative;top:2px;"value="4" checked>{{tr('delete')}}
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="sub_admins[]" style="    margin: 5px 10px;position:relative;top:2px;"value="4">{{tr('delete')}}
                   @php   }
                    if(in_array(10,$sub_admin))
                     {
                    @endphp
                   <input type="checkbox" name="sub_admins[]" style="    margin: 5px 10px;position:relative;top:2px;"value="10" checked>view sub-admin details
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="sub_admins[]" style="    margin: 5px 10px;position:relative;top:2px;"value="10">view sub-admin details
                   @php }
                 }
                   @endphp

                   @php if(!isset($user)) { @endphp
                   <input type="checkbox" name="sub_admins[]" style="    margin: 5px 10px;position:relative;top:2px;"value="1">{{tr('add')}}
                   <input type="checkbox" name="sub_admins[]" style="    margin: 5px 10px;position:relative;top:2px;"value="2">{{tr('edit')}}
                   <input type="checkbox" name="sub_admins[]" style="    margin: 5px 10px;position:relative;top:2px;"value="3">view all sub-admins
                   <input type="checkbox" name="sub_admins[]" style="    margin: 5px 10px;position:relative;top:2px;"value="4">{{tr('delete')}}
                   <input type="checkbox" name="sub_admins[]" style="    margin: 5px 10px;position:relative;top:2px;"value="10">view sub-admin details
                  @php } @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Ride Requests Management</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                    @php if(isset($user)) { $ride_requests_managemen = explode(',', $user->ride_requests_management);  

                     if(in_array(3,$ride_requests_managemen)!=0)
                     {
                     @endphp
                   <input type="checkbox" name="ride_requests_management[]" style="margin: 5px 10px;position:relative;top:2px;"value="3" checked>view all requests
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="ride_requests_management[]" style="margin: 5px 10px;position:relative;top:2px;"value="3">view all requests
                   @php   }
                    if(in_array(7,$ride_requests_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="ride_requests_management[]" style="margin: 5px 10px;position:relative;top:2px;"value="7" checked>view request
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="ride_requests_management[]" style="margin: 5px 10px;position:relative;top:2px;"value="7">view request
                    @php   }
                    if(in_array(12,$ride_requests_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="ride_requests_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="12" checked>re_assign
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="ride_requests_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="12">re_assign
                   @php   }
                    if(in_array(13,$ride_requests_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="ride_requests_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="13" checked>cancel request
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="ride_requests_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="13">cancel request
                   @php }
                 }
                   @endphp

                   @php if(!isset($user)) { @endphp
                   <input type="checkbox" name="ride_requests_management[]" style="margin: 5px 10px;position:relative;top:2px;"value="3">view all requests
                   <input type="checkbox" name="ride_requests_management[]" style="margin: 5px 10px;position:relative;top:2px;"value="7">view request
                   <input type="checkbox" name="ride_requests_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="12">re_assign
                   <input type="checkbox" name="ride_requests_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="13">cancel request
                  @php } @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Vehicle Types</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                    @php if(isset($user)) { $vehicle_type = explode(',', $user->vehicle_types);  

                     if(in_array(1,$vehicle_type)!=0)
                     {
                     @endphp
                  <input type="checkbox" name="vehicle_types[]" style="margin: 5px 10px;position:relative;top:2px;" value="1" checked>{{ tr('add') }}
                  @php 
                    }else{
                    @endphp
                  <input type="checkbox" name="vehicle_types[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                  @php   }
                    if(in_array(2,$vehicle_type))
                     {
                    @endphp
                   <input type="checkbox" name="vehicle_types[]" style="margin: 5px 10px;position:relative;top:2px;" value="2" checked>{{ tr('edit') }}
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="vehicle_types[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                    @php   }
                    if(in_array(3,$vehicle_type))
                     {
                    @endphp
                   <input type="checkbox" name="vehicle_types[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>view all vehicles
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="vehicle_types[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all vehicles
                    @php   }
                    if(in_array(4,$vehicle_type))
                     {
                    @endphp
                   <input type="checkbox" name="vehicle_types[]" style="margin: 5px 10px;position:relative;top:2px;" value="4" checked>{{ tr('delete') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="vehicle_types[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                   @php }
                 }
                   @endphp

                   @php if(!isset($user)) { @endphp
                    <input type="checkbox" name="vehicle_types[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                   <input type="checkbox" name="vehicle_types[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                   <input type="checkbox" name="vehicle_types[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all vehicles
                   <input type="checkbox" name="vehicle_types[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                  @php } @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Promo Codes</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                       @php if(isset($user)) { $promo_code = explode(',', $user->promo_codes);  

                     if(in_array(1,$promo_code)!=0)
                     {
                     @endphp
                  <input type="checkbox" name="promo_codes[]" style="    margin: 5px 10px;position:relative;top:2px;" value="1" checked>{{ tr('add') }}
                   @php 
                    }else{
                    @endphp
                  <input type="checkbox" name="promo_codes[]" style="    margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                  @php   }
                    if(in_array(2,$promo_code))
                     {
                    @endphp
                   <input type="checkbox" name="promo_codes[]" style="margin: 5px 10px;position:relative;top:2px;" value="2" checked>{{ tr('edit') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="promo_codes[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                   @php   }
                    if(in_array(3,$promo_code))
                     {
                    @endphp
                   <input type="checkbox" name="promo_codes[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>view all promo codes
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="promo_codes[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all promo codes
                   @php   }
                    if(in_array(4,$promo_code))
                     {
                    @endphp
                   <input type="checkbox" name="promo_codes[]" style="margin: 5px 10px;position:relative;top:2px;" value="4" checked>{{ tr('delete') }}
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="promo_codes[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                   @php
                  }
                }
                 @endphp

                 @php if(!isset($user)) { @endphp
                  <input type="checkbox" name="promo_codes[]" style="    margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                   <input type="checkbox" name="promo_codes[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                   <input type="checkbox" name="promo_codes[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all promo codes
                   <input type="checkbox" name="promo_codes[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                  @php } @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Rentals Management</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                    @php if(isset($user)) { $rental_managemen = explode(',', $user->rental_management);  

                     if(in_array(1,$rental_managemen)!=0)
                     {
                     @endphp
                  <input type="checkbox" name="rental_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="1" checked>{{ tr('add') }}
                   @php 
                    }else{
                    @endphp
                  <input type="checkbox" name="rental_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                  @php   }
                    if(in_array(2,$rental_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="rental_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="2" checked>{{ tr('edit') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="rental_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                    @php   }
                    if(in_array(3,$rental_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="rental_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>view all managements
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="rental_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all managements
                    @php   }
                    if(in_array(4,$rental_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="rental_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="4" checked>{{ tr('delete') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="rental_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                    @php
                  }
                }
                 @endphp

                 @php if(!isset($user)) { @endphp
                  <input type="checkbox" name="rental_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                   <input type="checkbox" name="rental_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                   <input type="checkbox" name="rental_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all managements
                   <input type="checkbox" name="rental_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                  @php } @endphp
                  </div>
                </div>

                  <div class="form-group">
                  <label class="col-sm-2 control-label lead"><b>Airport rides:</b></label>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Airport Details</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                    @php if(isset($user)) { $airport_detail = explode(',', $user->airport_details);  

                     if(in_array(1,$airport_detail)!=0)
                     {
                     @endphp
                  <input type="checkbox" name="airport_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                   @php 
                    }else{
                    @endphp
                     <input type="checkbox" name="airport_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                     @php   }
                    if(in_array(2,$airport_detail))
                     {
                    @endphp
                   <input type="checkbox" name="airport_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="airport_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                    @php   }
                    if(in_array(3,$airport_detail))
                     {
                    @endphp
                   <input type="checkbox" name="airport_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>view all airport details
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="airport_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all airport details
                     @php   }
                    if(in_array(4,$airport_detail))
                     {
                    @endphp
                   <input type="checkbox" name="airport_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="4" checked>{{ tr('delete') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="airport_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                    @php
                  }
                }
                 @endphp

                 @php if(!isset($user)) { @endphp
                   <input type="checkbox" name="airport_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                   <input type="checkbox" name="airport_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                   <input type="checkbox" name="airport_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all airport details
                   <input type="checkbox" name="airport_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                  @php } @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Destination details</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                    @php if(isset($user)) { $destination_detail = explode(',', $user->destination_details);  

                     if(in_array(1,$destination_detail)!=0)
                     {
                     @endphp
                  <input type="checkbox" name="destination_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="1" checked>{{ tr('add') }}
                  @php 
                    }else{
                    @endphp
                     <input type="checkbox" name="destination_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                    @php   }
                    if(in_array(2,$destination_detail))
                     {
                    @endphp
                   <input type="checkbox" name="destination_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="2" checked>{{ tr('edit') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="destination_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                    @php   }
                    if(in_array(3,$destination_detail))
                     {
                    @endphp
                   <input type="checkbox" name="destination_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>view all destination details
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="destination_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all destination details
                    @php   }
                    if(in_array(4,$destination_detail))
                     {
                    @endphp
                   <input type="checkbox" name="destination_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="4" checked>{{ tr('delete') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="destination_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                   @php
                  }
                }
                 @endphp

                 @php if(!isset($user)) { @endphp
                  <input type="checkbox" name="destination_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                   <input type="checkbox" name="destination_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                   <input type="checkbox" name="destination_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all destination details
                   <input type="checkbox" name="destination_details[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                  @php } @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Pricing Management</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                     @php if(isset($user)) { $pricing_managemen = explode(',', $user->pricing_management);  

                     if(in_array(1,$pricing_managemen)!=0)
                     {
                     @endphp
                  <input type="checkbox" name="pricing_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="1" checked>{{ tr('add') }}
                   @php 
                    }else{
                    @endphp
                     <input type="checkbox" name="pricing_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                      @php   }
                    if(in_array(2,$pricing_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="pricing_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="2" checked>{{ tr('edit') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="pricing_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                     @php   }
                    if(in_array(3,$pricing_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="pricing_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>view all pricing management
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="pricing_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all pricing management
                    @php   }
                    if(in_array(4,$pricing_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="pricing_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="4" checked>{{ tr('delete') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="pricing_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                   @php
                  }
                }
                 @endphp

                 @php if(!isset($user)) { @endphp
                  <input type="checkbox" name="pricing_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                   <input type="checkbox" name="pricing_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                   <input type="checkbox" name="pricing_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all pricing management
                   <input type="checkbox" name="pricing_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                  @php } @endphp
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label lead"><b>Ratings:</b></label>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Passenger Ratings</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                      @php if(isset($user)) { $provider_rating = explode(',', $user->provider_ratings);  

                     if(in_array(3,$provider_rating)!=0)
                     {
                     @endphp
                  <input type="checkbox" name="provider_ratings[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>view all passanger ratings
                    @php 
                    }else{
                    @endphp
                     <input type="checkbox" name="provider_ratings[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all passanger ratings
                     @php   }
                    if(in_array(4,$provider_rating))
                     {
                    @endphp
                  <input type="checkbox" name="provider_ratings[]" style="margin: 5px 10px;position:relative;top:2px;" value="4" checked>{{ tr('delete') }}
                   @php 
                    }else{
                    @endphp
                  <input type="checkbox" name="provider_ratings[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                  @php
                  }
                }
                 @endphp

                 @php if(!isset($user)) { @endphp
                  <input type="checkbox" name="provider_ratings[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all passanger ratings
                  <input type="checkbox" name="provider_ratings[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                  @php } @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">User Ratings</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                     @php if(isset($user)) { $user_rating = explode(',', $user->user_ratings);  

                     if(in_array(3,$user_rating)!=0)
                     {
                     @endphp
                  <input type="checkbox" name="user_ratings[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>view all user ratings
                  @php 
                    }else{
                    @endphp
                  <input type="checkbox" name="user_ratings[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all user ratings
                   @php   }
                    if(in_array(4,$user_rating))
                     {
                    @endphp
                  <input type="checkbox" name="user_ratings[]" style="margin: 5px 10px;position:relative;top:2px;" value="4" checked>{{ tr('delete') }}
                    @php 
                    }else{
                    @endphp
                  <input type="checkbox" name="user_ratings[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                   @php
                  }
                }
                 @endphp

                 @php if(!isset($user)) { @endphp
                  <input type="checkbox" name="user_ratings[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all user ratings
                  <input type="checkbox" name="user_ratings[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                  @php } @endphp
                  </div>
                </div>

                 <div class="form-group">
                  <label class="col-sm-2 control-label">Documents Management</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                    @php if(isset($user)) { $documents_managemen = explode(',', $user->documents_management);  

                     if(in_array(1,$documents_managemen)!=0)
                     {
                     @endphp
                  <input type="checkbox" name="documents_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="1" checked>{{ tr('add') }}
                  @php 
                    }else{
                    @endphp
                     <input type="checkbox" name="documents_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                     @php   }
                    if(in_array(2,$documents_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="documents_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="2" checked>{{ tr('edit') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="documents_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                    @php   }
                    if(in_array(3,$documents_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="documents_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>view all documents
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="documents_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all documents
                    @php   }
                    if(in_array(4,$documents_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="documents_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="4" checked>{{ tr('delete') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="documents_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                   @php
                  }
                }
                 @endphp

                 @php if(!isset($user)) { @endphp
                  <input type="checkbox" name="documents_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                   <input type="checkbox" name="documents_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                   <input type="checkbox" name="documents_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all documents
                   <input type="checkbox" name="documents_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                  @php } @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Currency Management</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                      @php if(isset($user)) { $currency_managemen = explode(',', $user->currency_management);  

                     if(in_array(1,$currency_managemen)!=0)
                     {
                     @endphp
                  <input type="checkbox" name="currency_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="1" checked>{{ tr('add') }}
                  @php 
                    }else{
                    @endphp
                  <input type="checkbox" name="currency_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                   @php   }
                    if(in_array(2,$currency_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="currency_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="2" checked>{{ tr('edit') }}
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="currency_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                   @php   }
                    if(in_array(3,$currency_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="currency_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>view all 
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="currency_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all 
                    @php   }
                    if(in_array(4,$currency_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="currency_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="4" checked>{{ tr('delete') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="currency_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                    @php
                  }
                }
                 @endphp

                 @php if(!isset($user)) { @endphp
                  <input type="checkbox" name="currency_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                   <input type="checkbox" name="currency_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                   <input type="checkbox" name="currency_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all 
                   <input type="checkbox" name="currency_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                  @php } @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Transactions</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                     @php if(isset($user)) { $transaction = explode(',', $user->transactions);  

                     if(in_array(3,$transaction)!=0)
                     {
                     @endphp
                  <input type="checkbox" name="transactions[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>{{ tr('view') }}
                   @php 
                    }else{
                    @endphp
                  <input type="checkbox" name="transactions[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">{{ tr('view') }}
                   @php
                  }
                }
                 @endphp

                 @php if(!isset($user)) { @endphp
                  <input type="checkbox" name="transactions[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">{{ tr('view') }}
                  @php } @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Push Notifications</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                    @php if(isset($user)) { $push_notifications = explode(',', $user->push_notifications);  

                     if(in_array(3,$push_notifications)!=0)
                     {
                     @endphp
                  <input type="checkbox" name="push_notifications[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>{{ tr('view') }}
                  @php 
                    }else{
                    @endphp
                     <input type="checkbox" name="push_notifications[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">{{ tr('view') }}
                      @php
                  }
                }
                 @endphp

                 @php if(!isset($user)) { @endphp
                   <input type="checkbox" name="push_notifications[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">{{ tr('view') }}
                  @php } @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Settings</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                     @php if(isset($user)) { $setting = explode(',', $user->settings);  

                     if(in_array(3,$setting)!=0)
                     {
                     @endphp
                  <input type="checkbox" name="settings[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>{{ tr('view') }}
                  @php 
                    }else{
                    @endphp
                  <input type="checkbox" name="settings[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">{{ tr('view') }}
                     @php
                  }
                }
                 @endphp

                 @php if(!isset($user)) { @endphp
                  <input type="checkbox" name="settings[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">{{ tr('view') }}
                  @php } @endphp
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Advertisement Management</label>
                  <div class="col-sm-8" style="margin: 5px 0;">
                    @php if(isset($user)) { $ads_managemen = explode(',', $user->ads_management);  

                     if(in_array(1,$ads_managemen)!=0)
                     {
                     @endphp 
                  <input type="checkbox" name="ads_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="1" checked>{{ tr('add') }}
                  @php 
                    }else{
                    @endphp
                  <input type="checkbox" name="ads_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                    @php   } if(in_array(2,$ads_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="ads_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="2" checked>{{ tr('edit') }}
                   @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="ads_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                     @php   } if(in_array(3,$ads_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="ads_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="3" checked>view all 
                     @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="ads_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all 
                    @php   } if(in_array(4,$ads_managemen))
                     {
                    @endphp
                   <input type="checkbox" name="ads_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="4" checked>{{ tr('delete') }}
                    @php 
                    }else{
                    @endphp
                   <input type="checkbox" name="ads_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                    @php
                  }
                }
                 @endphp

                 @php if(!isset($user)) { @endphp
                  <input type="checkbox" name="ads_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="1">{{ tr('add') }}
                   <input type="checkbox" name="ads_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="2">{{ tr('edit') }}
                   <input type="checkbox" name="ads_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="3">view all 
                   <input type="checkbox" name="ads_management[]" style="margin: 5px 10px;position:relative;top:2px;" value="4">{{ tr('delete') }}
                  @php } @endphp
                  </div>
                </div>


                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-danger">{{tr('cancel')}}</button>
                    <button type="submit" class="btn btn-success pull-right">{{tr('submit')}}</button>
                </div>

              </form>

            </div>

        </div>

    </div>

@endsection
