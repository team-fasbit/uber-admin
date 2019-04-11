@extends('layouts.admin')

@if(isset($name))
  @section('title', tr('edit_airport_pricing'))
@else
  @section('title', 'Pricing Setup')
@endif


@section('content-header', 'Pricing Management')

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>Home</a></li>
    <li><a href="{{route('admin.airport_pricings')}}"><i class="fa fa-user"></i> {{tr('airport_pricings')}}</a></li>
    <li class="active">{{tr('airport_pricings')}}</li>
@endsection

@section('content')

@include('notification.notify')

<div class="row">

  <div class="col-md-12">

      <div class="box box-info">

          <div class="box-header">
                <div class="map_content">
                <p class="lead para_mid">
                    <b>What is this?</b>
                 </p>
                 <p class="lead">
                    Step 1: You have added an Airport already
                </p>
                <p class="lead">
                     Step 2: You have also Added Destination's already.
                </p>
                   <p class="lead para_mid">
                    Now we need to create a pricing package for each drop.
                 </p>
                    <p class="lead para_mid">
                    Use this screen to map an Airport with a particular destination and configure a customized pricing plan ( by also adding any extra's like Tolls etc. )
                 </p>
              </div>
              </div>
          <div class="panel-heading border">

          </div>

          <form class="form-horizontal bordered-group" action="{{route('admin.airport_pricing.process')}}" method="POST" enctype="multipart/form-data" role="form">

            <div class="form-group">
              <label class="col-sm-2 control-label">Airport name</label>
              <div class="col-sm-8">
                <select name="airport_detail" required class="form-control">
                    <option value="select airport">{{ tr('select_airport') }} </option>
                    @foreach($airport_details as $airport_detail)
                    @if(isset($airport_price->airport_details_id))
                      @if($airport_price->airport_details_id == $airport_detail->id)
                      <option value="{{$airport_detail->id}}" selected="true">{{$airport_detail->name}}</option>
                      @else
                      <option value="{{$airport_detail->id}}">{{$airport_detail->name}}</option>
                      @endif
                    @else
                    <option value="{{$airport_detail->id}}">{{$airport_detail->name}}</option>
                    @endif
                    @endforeach
                </select>
                
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">{{ tr('location_details') }}</label>
              <div class="col-sm-8">
                <select name="location_detail" required class="form-control">
                    <option value="select service type">{{ tr('select_location') }} </option>
                    @foreach($location_details as $location_detail)
                    @if(isset($airport_price->location_details_id))
                      @if($airport_price->location_details_id == $location_detail->id)
                      <option value="{{$location_detail->id}}" selected="true">{{$location_detail->name}}</option>
                      @else
                      <option value="{{$location_detail->id}}">{{$location_detail->name}}</option>
                      @endif
                    @else
                    <option value="{{$location_detail->id}}">{{$location_detail->name}}</option>
                    @endif
                    @endforeach
                </select>
                
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Vehicle Type</label>
              <div class="col-sm-8">
                <select name="service_type" required class="form-control">
                    <option value="select service type">Select Vehicle Type</option>
                    @foreach($service_types as $service_type)
                    @if(isset($airport_price->service_type_id))
                      @if($airport_price->service_type_id == $service_type->id)
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
            
            <div class="form-group">
              <label class="col-sm-2 control-label">{{ tr('price') }}</label>
              <div class="col-sm-8">
                <input placeholder="Eg: 100" type="text" name="price" value="{{ isset($airport_price->price) ? $airport_price->price : '' }}" required class="form-control">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">Number of Tolls in between</label>
              <div class="col-sm-8">
                <input placeholder="Eg: 2" type="text" name="number_tolls" value="{{ isset($airport_price->number_tolls) ? $airport_price->number_tolls : '' }}" required class="form-control">
              </div>
            </div>


            <input type="hidden" name="id" value="@if(isset($airport_price)) {{$airport_price->id}} @endif" />
                <div class="box-header">
                <div class="map_content">
                <p class="lead para_mid">
                  Why like this?
                 </p>
                 <p class="lead">
                  Airport rentals are a bit complicated. We have simplified it. Usually Airport rentals are managed in a totally different manner than normal taxi dispatch ( as each Airport / Taxi association etc. have their own rules that you would need to adhere to ). You would also need to add Toll Booth ticket prices to the ride, Special extra fee's, Tax etc. that are levied by the respective Airport's & associations. So we have simplified it in a manner that you get a very flexible solution.
                </p>
              
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
