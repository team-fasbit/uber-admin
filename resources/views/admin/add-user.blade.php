@extends('layouts.admin')

@if(isset($name))
  @section('title', tr('edit_user'))
@else
  @section('title', tr('add_user'))
@endif

@if(isset($name))
  @section('content-header', 'Edit a passenger')
@else
  @section('content-header', 'Add a passenger')
@endif


@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li><a href="{{route('admin.users')}}"><i class="fa fa-users"></i> {{tr('users')}}</a></li>
    @if(isset($name))
      <li class="active">{{tr('edit_user')}}</li>
    @else
      <li class="active">{{tr('add_user')}}</li>
    @endif

@endsection

@section('content')

@include('notification.notify')

    <div class="row">

        <div class="col-md-12">

            <div class="box box-info">
                <div class="box-header">
                    <div class="map_content">
                      <p class="lead para_mid">
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
                       </p>  
                    </div>
                </div>
              <form class="form-horizontal bordered-group" action="{{route('admin.save.user')}}" method="POST" enctype="multipart/form-data" role="form">
                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ tr('first_name') }}</label>
                  <div class="col-sm-8">
                    <input type="text" name="first_name" value="{{ isset($user->first_name) ? $user->first_name : '' }}" required class="form-control">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ tr('last_name') }}</label>
                  <div class="col-sm-8">
                    <input type="text" name="last_name" value="{{ isset($user->last_name) ? $user->last_name : '' }}" required class="form-control">
                  </div>
                </div>

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
                    <input type="email" name="email" value="{{ isset($user->email) ? $user->email : '' }}" required class="form-control">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">{{ tr('contact_num') }} </label>
                  <div class="col-sm-8">
                    <input type="text" name="mobile"  value="{{ isset($user->mobile) ? $user->mobile : '' }}" required class="form-control">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Pickup Location</label>
                  <div class="col-sm-8">
                    <textarea name="address" required class="form-control" rows="3">{{ isset($user->address) ? $user->address : '' }}</textarea>
                  </div>
                </div>

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

                <div class="box-footer">
                    <button type="reset" class="btn btn-danger">{{tr('cancel')}}</button>
                    <button type="submit" class="btn btn-success pull-right">{{tr('submit')}}</button>
                </div>

              </form>

            </div>

        </div>

    </div>

@endsection
