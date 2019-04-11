@extends('layouts.admin')

@if(isset($name))
  @section('title', tr('edit_service_type'))
@else
  @section('title', 'Add a Vehicle Type')
@endif


@section('content-header', tr('service_types'))

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>Home</a></li>
    <li><a href="{{route('admin.service.types')}}"><i class="fa fa-user"></i> {{tr('service_types')}}</a></li>
    <li class="active">Add a Vehicle Type</li>
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
            <p class="lead ">
             * Use this screen to Add a Vehicle type Ex: Sedan, Hatch back, Saloon
            </p>
             <p class="lead ">
            * The vehicle type you add here, will appear on the Passenger mobile app <a data-toggle="modal" href="#myModal">( Refer the screen shot present below the form )</a>
            </p>
          </div>
          </div>
          <!-- <div class="box-header">
            @if(isset($name))
            {{ tr('edit_service') }}
            @else
              {{ tr('create_service') }}
            @endif
          </div> -->
          <div class="panel-heading border">

          </div>

          <form class="form-horizontal bordered-group" action="{{route('admin.add.service.process')}}" method="POST" enctype="multipart/form-data" role="form">
            <div class="form-group">
              <label class="col-sm-2 control-label">Vehicle Type</label>
              <div class="col-sm-8">
                <input placeholder="Eg: Sedan" type="text" name="service_name" value="{{ isset($service->name) ? $service->name : '' }}" required class="form-control">
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2 control-label">{{ tr('number_seats') }}</label>
              <div class="col-sm-8">
                <input placeholder="Eg: 4" type="text" name="number_seat" value="{{ isset($service->number_seat) ? $service->number_seat : '' }}" required class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Base Fare</label>
              <div class="col-sm-8">
                <input placeholder="Eg: 5" type="text" name="base_fare" value="{{ isset($service->base_fare) ? $service->base_fare : '' }}" required class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">{{ tr('minimum_fare') }}</label>
              <div class="col-sm-8">
                <input placeholder="Eg: 10" type="text" name="min_fare" value="{{ isset($service->min_fare) ? $service->min_fare : '' }}" required class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Booking Fee</label>
              <div class="col-sm-8">
                <input placeholder="Eg: 0.5" type="text" name="booking_fee" value="{{ isset($service->booking_fee) ? $service->booking_fee : '' }}" required class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Tax Fee</label>
              <div class="col-sm-8">
                <input placeholder="Eg: 0.2" type="text" name="tax_fee" value="{{ isset($service->tax_fee) ? $service->tax_fee : '' }}" required class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">{{ tr('price_per_min') }}</label>
              <div class="col-sm-8">
                <input placeholder="Eg: 10" type="text" name="price_per_min" value="{{ isset($service->price_per_min) ? $service->price_per_min : '' }}" required class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Price per Mile / Kms</label>
              <div class="col-sm-8">
                <input placeholder="Eg: 5" type="text" name="price_per_unit_distance" value="{{ isset($service->price_per_unit_distance) ? $service->price_per_unit_distance : '' }}" required class="form-control">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Mile -or- Kms?</label>
              <div class="col-sm-8">
              <select name="distance_unit" value="" required class="form-control">
              <option value="">{{ tr('select') }}</option>
              <option value="miles" @if(isset($service->distance_unit)) @if($service->distance_unit == 'miles') selected @endif @endif>miles</option>
              <option value="kms" @if(isset($service->distance_unit)) @if($service->distance_unit == 'kms') selected @endif @endif>kms</option>
              </select>
                <!-- <input placeholder="Eg: kms or miles" type="text" name="distance_unit" value="{{ isset($service->distance_unit) ? $service->distance_unit : '' }}" required class="form-control"> -->
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">{{ tr('picture') }}</label>
              <div class="col-sm-8">
              @if(isset($service->picture))
              <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{$service->picture}}">
              @endif
                <input name="picture" type="file">
                <p class="help-block">{{ tr('upload_message') }}</p>
              </div>
            </div>
            
             <div class="checkbox add_service_type_checkbox">
                  <label class="col-sm-2">
                    <input name="is_default" @if(isset($service)) @if($service->status ==1) checked  @else  @endif @endif  value="1"  type="checkbox">{{ tr('set_default') }}</label>
              </div>
            <input type="hidden" name="id" value="@if(isset($service)) {{$service->id}} @endif" />

            <div class="box-footer">
                <a href="{{ route('admin.service.types' ) }}" class="btn btn-danger">{{tr('cancel')}}</a>
                <button type="submit" class="btn btn-success pull-right">{{tr('submit')}}</button>
            </div>

          </form>

      </div>

  </div>

</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="myModalLabel">Add a Vehicle type</h4>
      </div>
      <div class="modal-body">
        <img src="../images/Vehicle-Type.png" alt="img" width="100%">
      </div>
<!--       <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>

@endsection
