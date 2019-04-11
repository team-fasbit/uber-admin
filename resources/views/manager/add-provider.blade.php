@extends('layouts.corporate')

@if(isset($name))
  @section('title', tr('edit_provider'))
@else
  @section('title', tr('add_provider'))
@endif

@if(isset($name))
  @section('content-header', tr('edit_provider'))
@else
  @section('content-header', tr('add_provider'))
@endif


@section('breadcrumb')
    <li><a href="{{route('corporate.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li><a href="{{route('corporate.providers')}}"><i class="fa fa-users"></i> {{tr('providers')}}</a></li>
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
                </div>

                <form class="form-horizontal bordered-group" action="{{route('corporate.save.provider')}}" method="POST" enctype="multipart/form-data" role="form">
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
                      <label class="col-sm-2 control-label">{{ tr('contact_num') }} </label>
                      <div class="col-sm-8">
                        <input type="number" name="mobile"  value="{{ isset($provider->mobile) ? $provider->mobile : old('mobile') }}" required class="form-control">
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
                      <label class="col-sm-2 control-label">{{ tr('paypal_email') }}</label>
                      <div class="col-sm-8">
                        <input type="email" name="paypal_email" value="{{ isset($provider->paypal_email) ? $provider->paypal_email : old('paypal_email') }}" required class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ tr('service_type') }}</label>
                      <div class="col-sm-8">
                        <select name="service_type" required class="form-control">
                            <option value="select service type">{{ tr('select_service') }} </option>
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
