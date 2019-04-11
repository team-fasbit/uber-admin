@extends('layouts.admin')

@if(isset($name))
  @section('title', tr('edit_location_detail'))
@else
  @section('title', 'Add Location detail')
@endif


@section('content-header', 'Add a destination')

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>Home</a></li>
    <li><a href="{{route('admin.location_details')}}"><i class="fa fa-user"></i> </a></li>
    <li class="active">Add a destination</li>
@endsection

@section('content')

@include('notification.notify')

<div class="row">

  <div class="col-md-12">

      <div class="box box-info">
        <div class="box-header">
                <div class="map_content">
                <p class="lead para_mid">
                 - Use this screen to Add as many destinations you would need.
                 </p>
                 <p class="lead para_mid">
                  A destination is the Drop location where the passenger would like to goto from the Airport Eg: SFO Airport to San Jose Railway station.
                </p>
                <p class="lead">
                 <b> Why add a destination?</b>
                </p>
                <p class="lead">
                  Airport rentals are a bit complicated. We have simplified it. Usually Airport rentals are managed in a totally different manner than normal taxi dispatch ( as each Airport / Taxi association etc. have their own rules that you would need to adhere to ). You would also need to add Toll Booth ticket prices to the ride, Special extra fee's, Tax etc. that are levied by the respective Airport's & associations. So we have simplified it in a manner that you get a very flexible solution.
                </p>
                <p class="lead para_mid"><b>Good news:</b> While we do your installation, we will bulk upload all the destinations & zip-codes for all the Airports in your city</p>
              </div>
              </div>
         
          <div class="panel-heading border">

          </div>

          <form class="form-horizontal bordered-group" action="{{route('admin.location_detail.process')}}" method="POST" enctype="multipart/form-data" role="form">

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
              <label class="col-sm-2 control-label">Destination</label>
              <div class="col-sm-8">
                <input id="txtPlaces" placeholder="Eg: International location" type="text" name="name" value="{{ isset($location_detail->name) ? $location_detail->name : '' }}" required class="form-control">
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-2 control-label">Zip-code</label>
              <div class="col-sm-8">
                <input placeholder="Eg: 560100" type="text" name="zipcode" value="{{ isset($location_detail->zipcode) ? $location_detail->zipcode : '' }}" required class="form-control">
              </div>
            </div>


            <input type="hidden" name="id" value="@if(isset($location_detail)) {{$location_detail->id}} @endif" />

            <div class="box-footer">
                <button type="reset" class="btn btn-danger">{{tr('cancel')}}</button>
                <button type="submit" class="btn btn-success pull-right">{{tr('submit')}}</button>
            </div>

          </form>

      </div>

  </div>

</div>


@endsection
