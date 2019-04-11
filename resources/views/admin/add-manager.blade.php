@extends('layouts.admin')

@if(isset($name))
  @section('title', tr('edit_manager'))
@else
  @section('title', tr('add_manager'))
@endif

@if(isset($name))
  @section('content-header', tr('edit_manager'))
@else
  @section('content-header', 'Add a Manager')
@endif


@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li><a href="{{route('admin.managers')}}"><i class="fa fa-users"></i> {{tr('managers')}}</a></li>
    @if(isset($name))
      <li class="active">{{tr('edit_manager')}}</li>
    @else
      <li class="active">{{tr('add_manager')}}</li>
    @endif

@endsection

@section('content')

@include('notification.notify')

    <div class="row">

        <div class="col-md-12">

            <div class="box box-info">

                <div class="box-header">
                  <div class="map_content">
                    <p class="lead">
                         <b>What is this?</b>
                    </p>
                     <p class="lead para_mid">
                     Simple. You are the call center manager of this Taxi aggregater company. Now you would like to Add more Taxi Businesses call center managers under you.
                     </p>
                     <p class="lead ">For example: click <a href="{{asset('manager/login')}}" target="_blank">HERE</a> to see a sample call-center dashboard.</p>
                     
                 </div>
                </div>

                <form class="form-horizontal bordered-group" action="{{route('admin.save.manager')}}" method="POST" enctype="multipart/form-data" role="form">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Business Name</label>
                      <div class="col-sm-8">
                        <input type="text" name="name" value="{{ isset($corporate->name) ? $corporate->name : old('name') }}" required class="form-control">
                      </div>
                    </div>

                
                   <input type="hidden" name="id" value="@if(isset($corporate)) {{$corporate->id}} @endif" />
                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ tr('gender') }}</label>

                      <div class="col-sm-8">
                        <div class="radio">
                          <label>
                            <input name="gender" @if(isset($corporate)) @if($corporate->gender == 'male') checked @endif @endif value="male" type="radio">{{ tr('male') }}</label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio"@if(isset($corporate)) @if($corporate->gender == 'female') checked @endif @endif name="gender" value="female">{{ tr('female') }}</label>
                        </div>
                        <div class="radio">
                          <label>
                            <input type="radio"@if(isset($corporate)) @if($corporate->gender == 'others') checked @endif @endif name="gender" value="others">{{ tr('others') }}</label>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ tr('email') }}</label>
                      <div class="col-sm-8">
                        <input type="email" name="email" value="{{ isset($corporate->email) ? $corporate->email : old('email') }}" required class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ tr('contact_num') }} </label>
                      <div class="col-sm-8">
                        <input type="text" name="mobile"  value="{{ isset($corporate->mobile) ? $corporate->mobile : old('mobile') }}" required class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ tr('address') }}</label>
                      <div class="col-sm-8">
                        <textarea name="address" required class="form-control" rows="3">{{ isset($corporate->address) ? $corporate->address : old('address') }}</textarea>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ tr('profile_pic') }}</label>
                      <div class="col-sm-8">
                      @if(isset($corporate->picture))
                      <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{$corporate->picture}}">
                      @endif
                        <input name="picture" type="file">
                        <p class="help-block">{{ tr('upload_message') }}</p>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">{{ tr('paypal_email') }}</label>
                      <div class="col-sm-8">
                        <input type="email" name="paypal_email" value="{{ isset($corporate->paypal_email) ? $corporate->paypal_email : old('paypal_email') }}" required class="form-control">
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
