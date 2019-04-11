@extends('layouts.admin')

@if(isset($name))
  @section('title', tr('edit_provider'))
@else
  @section('title', tr('add_provider'))
@endif

@if(isset($name))
  @section('content-header', tr('edit_provider'))
@else
  @section('content-header', 'Add a Driver')
@endif


@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li><a href="{{route('admin.providers')}}"><i class="fa fa-users"></i> {{tr('providers')}}</a></li>
    @if(isset($name))
      <li class="active">{{tr('edit_provider')}}</li>
    @else
      <li class="active">{{tr('add_provider')}}</li>
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
                       Use this screen to Add a Driver to your business.
                    </p>
                     <p class="lead para_mid">
                     <b>Note:</b> While Adding a Driver, you can also assign him to a corporate ( who have earlier registered with you ). To know more about the 'Corporate' feature, please goto the <b>Corporate's</b> section present on the menu in the left side.
                     </p>
                   
                 </div>
                </div>

                <form class="form-horizontal bordered-group" action="{{route('admin.save.provider')}}" method="POST" enctype="multipart/form-data" role="form">

                  <div class="form-group">
                      <label class="col-sm-2 control-label">Assign under a corporate?</label>
                      <div class="col-sm-8">
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
                      <label class="col-sm-2 control-label">{{ tr('first_name') }}</label>
                      <div class="col-sm-8">
                        <input type="text" name="first_name" value="{{ isset($provider->first_name) ? $provider->first_name : old('first_name') }}" required class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ tr('last_name') }}</label>
                      <div class="col-sm-8">
                        <input type="text" name="last_name" value="{{ isset($provider->last_name) ? $provider->last_name : old('last_name') }}" required class="form-control">
                      </div>
                    </div>
                   <input type="hidden" name="id" value="@if(isset($provider)) {{$provider->id}} @endif" />
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ tr('gender') }}</label>

                      <div class="col-sm-8">
                        <div class="radio">
                          <label>
                            <input name="gender" @if(isset($provider)) @if($provider->gender == 'male') checked @endif @endif value="male" type="radio">{{ tr('male') }}</label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio"@if(isset($provider)) @if($provider->gender == 'female') checked @endif @endif name="gender" value="female">{{ tr('female') }}</label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio"@if(isset($provider)) @if($provider->gender == 'others') checked @endif @endif name="gender" value="others">{{ tr('others') }}</label>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ tr('email') }}</label>
                      <div class="col-sm-8">
                        <input type="email" name="email" value="{{ isset($provider->email) ? $provider->email : old('email') }}" required class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">Password</label>
                      <div class="col-sm-8">
                        <input type="password" name="password" value="" class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">Confirm Password</label>
                      <div class="col-sm-8">
                        <input type="password" name="password_confirmation" value="" class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ tr('contact_num') }} </label>
                      <div class="col-sm-8">
                        <input type="text" name="mobile"  value="{{ isset($provider->mobile) ? $provider->mobile : old('mobile') }}" required class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">Plate No</label>
                      <div class="col-sm-8">
                        <input placeholder="Eg: 12345" type="text" name="plate_no" value="{{ isset($provider->plate_no) ? $provider->plate_no : '' }}" required class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Brand</label>
                      <div class="col-sm-8">
                        <input placeholder="Eg: Ferrari" type="text" name="model" value="{{ isset($provider->model) ? $provider->model : '' }}" required class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Color</label>
                      <div class="col-sm-8">
                        <input placeholder="Eg: Black" type="text" name="color" value="{{ isset($provider->color) ? $provider->color : '' }}" required class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ tr('address') }}</label>
                      <div class="col-sm-8">
                        <textarea name="address" required class="form-control" rows="3">{{ isset($provider->address) ? $provider->address : old('address') }}</textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ tr('profile_pic') }}</label>
                      <div class="col-sm-8">
                      @if(isset($provider->picture))
                      <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{$provider->picture}}">
                      @endif
                        <input name="picture" type="file">
                        <p class="help-block">{{ tr('upload_message') }}</p>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">Car Image</label>
                      <div class="col-sm-8">
                      @if(isset($provider->car_image))
                      <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{$provider->car_image}}">
                      @endif
                        <input name="car_image" type="file">
                        <p class="help-block">{{ tr('upload_message') }}</p>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ tr('paypal_email') }}</label>
                      <div class="col-sm-8">
                        <input type="email" name="paypal_email" value="{{ isset($provider->paypal_email) ? $provider->paypal_email : old('paypal_email') }}" required class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">Vehicle Type</label>
                      <div class="col-sm-8">
                        <select name="service_type" required class="form-control">
                            <option value="select service type">Select the Vehicle Type</option>
                            @foreach($service_types as $service_type)
                            @if(isset($provider_type))
                              @if($provider_type == $service_type->id)
                              <option value="{{$service_type->id}}" selected="true">{{$service_type->name}}</option>
                              @else
                              <option value="{{$service_type->id}}">{{$service_type->name}}</option>
                              @endif
                            @else
                            <option value="{{$service_type->id}}">{{$service_type->name}}</option>
                            @endif
                            @endforeach
                        </select>
                        
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
