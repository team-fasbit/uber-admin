@extends('layouts.admin')

@if(isset($name))
  @section('title', tr('edit_airport_detail'))
@else
  @section('title', 'Add an Airport Details')
@endif


@section('content-header', tr('airport_details'))

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>Home</a></li>
    <li><a href="{{route('admin.airport_details')}}"><i class="fa fa-user"></i> {{tr('airport_details')}}</a></li>
    <li class="active">{{tr('airport_details')}}</li>
@endsection

@section('content')

@include('notification.notify')

<div class="row">

  <div class="col-md-12">

      <div class="box box-info">
          <div class="box-header">
        <div class="map_content">
            <p class="lead ">
             <b>What is this?</b>
            </p>
             <p class="lead ">
              We understand Airport rides is a very important part of Cab rental business. So we have made this as a standalone feature in our platform :)
            </p>
            <p class="lead">
            Now, you can effortlessly handle those high volume Airport pickup's and Drop's in an efficient manner.
            </p>
          </div>
          </div>
        
          <div class="panel-heading border">

          </div>

          <form class="form-horizontal bordered-group" action="{{route('admin.airport_detail.process')}}" method="POST" enctype="multipart/form-data" role="form">
          <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key={{ $key }}&sensor=false&libraries=places"></script>
            <script type="text/javascript">
                google.maps.event.addDomListener(window, 'load', function () {
                    var places = new google.maps.places.Autocomplete(document.getElementById('txtPlaces'));
                    google.maps.event.addListener(places, 'place_changed', function () {
                        var place = places.getPlace();
                        var address = place.formatted_address;
                        var latitude = place.geometry.location.lat();
                        var longitude = place.geometry.location.lng();
                        var mesg = "Address: " + address;
                        mesg += "\nLatitude: " + latitude;
                        mesg += "\nLongitude: " + longitude;
                        document.getElementById("latitude").value = latitude;
                        document.getElementById("longitude").value = longitude;
                        console.log(lati,longi);
                    });
                });
            </script>
            <input type="hidden" value="" name = "latitude" id="latitude" />
            <input type="hidden" value="" name ="longitude" id="longitude" />

            <div class="form-group">
              <label class="col-sm-2 control-label">Airport Name</label>
              <div class="col-sm-8">
                <input id="txtPlaces" placeholder="Eg: International Airport" type="text" name="name" value="{{ isset($airport_detail->name) ? $airport_detail->name : '' }}" required class="form-control">
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2 control-label">Zip-code</label>
              <div class="col-sm-8">
                <input placeholder="Eg: 560100" type="text" name="zipcode" value="{{ isset($airport_detail->zipcode) ? $airport_detail->zipcode : '' }}" required class="form-control">
              </div>
            </div>


            <input type="hidden" name="id" value="@if(isset($airport_detail)) {{$airport_detail->id}} @endif" />

            <div class="box-footer">
                <button type="reset" class="btn btn-danger">{{tr('cancel')}}</button>
                <button type="submit" class="btn btn-success pull-right">{{tr('submit')}}</button>
            </div>

          </form>

      </div>

  </div>

</div>


@endsection
