@extends('layouts.admin')

@if(isset($name))
  @section('title', tr('edit_hourly_package'))
@else
  @section('title', tr('add_hourly_package'))
@endif

@section('content-header', 'Rentals Management')

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>Home</a></li>
    <li><a href="{{route('admin.service.types')}}"><i class="fa fa-user"></i>Rentals Management</a></li>
    <li class="active">Add a Hourly Package</li>
@endsection

@section('content')

@include('notification.notify')
<style>
  @media (min-width: 768px)
  {
    .modal-dialog {
    width: 400px;
    margin: 30px auto;
  }
  }

</style>
<div class="row">

  <div class="col-md-12">

      <div class="box box-info">
        <div class="box-header">
        <div class="map_content">
            <p class="lead para_mid">
              <b>What is this?</b>
            </p>
             <p class="lead para_mid">
             Upon popular request from customers, we have added Hour based rentals feature as well in our platform.
            </p>
            <p class="lead para_mid">
            Using this screen, you can Add Hourly packages. Your passengers can choose any package of their choice and Pre-book it directly from the mobile app.
            </p>
            <p class="lead para_mid">
             Any package you add here, will reflect in the passenger app <a href="#rentalmodal" data-toggle="modal">( check the screenshot present below the form )</a>
            </p>
            <p class="lead para_mid">
              While you can do a Taxi dispatch business using our platform. 
            </p>

          </div>
          </div>
          
          <div class="panel-heading border">

          </div>

          <form class="form-horizontal bordered-group" action="{{route('admin.hourly_package.process')}}" method="POST" enctype="multipart/form-data" role="form">
            <div class="form-group">
              <label class="col-sm-2 control-label">{{ tr('number_hours') }}</label>
              <div class="col-sm-8">
                <input placeholder="Eg: 2" type="text" name="number_hours" value="{{ isset($hourly_package->number_hours) ? $hourly_package->number_hours : '' }}" required class="form-control">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">{{ tr('price') }}</label>
              <div class="col-sm-8">
                <input placeholder="Eg: 10" type="text" name="price" value="{{ isset($hourly_package->price) ? $hourly_package->price : '' }}" required class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">For how many miles?</label>
              <div class="col-sm-8">
                <input placeholder="Eg: 50" type="text" name="distance" value="{{ isset($hourly_package->distance) ? $hourly_package->distance : '' }}" required class="form-control">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Vehicle Type</label>
              <div class="col-sm-8">
                <select name="service_type" required class="form-control">
                    <option value="select service type">Select Vehicle Type                  
        </option>
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
          
            <input type="hidden" name="id" value="@if(isset($hourly_package)) {{$hourly_package->id}} @endif" />

            <div class="box-footer">
                <button type="reset" class="btn btn-danger">{{tr('cancel')}}</button>
                <button type="submit" class="btn btn-success pull-right">{{tr('submit')}}</button>
            </div>

          </form>

      </div>

  </div>

<!-- Modal -->
<div class="modal fade" id="rentalmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel">Add a Vehicle type</h4>
      </div>
      <div class="modal-body">
        <img src="../images/Rentals.png" alt="img" width="100%">
      </div>
<!--       <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>
</div>


@endsection
