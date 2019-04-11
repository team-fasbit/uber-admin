@extends('layouts.admin')

@section('title', 'Request View | ')

@section('content')

@include('notification.notify')

<div class="service_details box box-info"></div>

<div class="panel">
<!-- <<<<<<< HEAD
    <div class="panel-heading border">
        
    </div>

    <div class="panel-body ">
    <div class="row">
======= -->

    <div class="panel-body">
<!--         <div class="row">
>>>>>>> bcd9609c7c6b1f12c8e37120addf6527fb8bb57f
            <div class="col-md-10">
                <input id="pac-input" class="controls" type="text" placeholder="Enter a location">
            </div>
            <div class="col-md-2">
                <button class="btn btn-success controls" id="location-search">Search</button>
            </div>
        </div> -->
        <div class="row">
            <h3 class="text-center">Trip Details</h3>
            <div class="col-md-6 col-md-offset-3">

            <div class="box box-widget widget-user-2">

                <!--<div class="widget-user-header bg-gray">
                    <div class="widget-user-image">
                        <img class="img-circle" src=" https://lh6.googleusercontent.com/-OZAi3M62YwI/AAAAAAAAAAI/AAAAAAAAACA/i7ebxuUrUHM/photo.jpg?sz=50 " alt="User Avatar">
                    </div>

                    <h3 class="widget-user-username">tolga togay </h3>
                    <h5 class="widget-user-desc">Moderator</h5>
                </div>-->

                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li>
                            <a href="javascript:void(0);">{{ tr('booked_by') }} <span class="pull-right">{{$request->user_first_name}}</span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Driver Name<span class="pull-right">{{$request->provider_first_name}}</span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">{{ tr('total_time') }}<span class="pull-right"> {{$request->total_time}}</span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Trip started on<span class="pull-right">{{ date('jS \of F Y h:i:s A', strtotime($request->start_time)) }} </span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Trip ended on<span class="pull-right">{{ date('jS \of F Y h:i:s A', strtotime($request->end_time)) }}</span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">{{ tr('base_price') }}<span class="pull-right">{{ get_currency_value($request->base_price) }}</span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Extra time price<span class="pull-right">{{ get_currency_value($request->time_price) }}</span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Tax<span class="pull-right"> {{ get_currency_value($request->tax) }}</span>
                            </a>
                         </li>
                        <li>
                            <a style='font-weight: 800' href="javascript:void(0);">{{ tr('total_amount') }}<span class="pull-right"> {{ get_currency_value($request->total_amount) }}</span></a>
                        </li>
                         <li>
                            <a href="javascript:void(0);">Source:<span class="pull-right">{{$request->request_address}}</span></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Destination:<span class="pull-right">{{$request->request_address1}}</span></a>
                        </li>
                    </ul>
                </div>
            
            </div>

        </div>
        </div>
        <!--  <div class="col-md-12">
            <div class="row">
              <div class="col-md-12">
                <strong>{{ tr('booked_by') }} :</strong> {{$request->user_first_name}} <br>
                <strong>{{ tr('provider_name') }} :</strong> {{$request->provider_first_name}} <br>
                <strong>{{ tr('total_time') }} :</strong> {{$request->total_time}} <br>
                <strong>{{ tr('request_started') }} :</strong> {{ date('jS \of F Y h:i:s A', strtotime($request->start_time)) }} <br>
                <strong>{{ tr('request_ended') }} :</strong> {{ date('jS \of F Y h:i:s A', strtotime($request->end_time)) }} <br>
                <strong>{{ tr('base_price') }} :</strong> {{ get_currency_value($request->base_price) }} <br>
                <strong>{{ tr('time_price') }} :</strong> {{ get_currency_value($request->time_price) }} <br>
                <strong>{{ tr('tax_price') }} :</strong> {{ get_currency_value($request->tax) }} <br>
                <strong>{{ tr('total_amount') }} :</strong> {{ get_currency_value($request->total_amount) }} <br>
                <strong>{{ tr('address') }} :</strong> {{$request->request_address}}
              </div>
            </div>
        </div> -->
        @if($request->before_image !='')
        <div class="col-md-6">
            <div class="row">
              <div class="col-md-12">
                <section class="widget bg-white post-comments">
                    <div class="widget bg-success mb0 text-center no-radius"><strong>{{ tr('before_service') }}</strong></div>
                        <div class="media">
                            <img style="width:100%;" src="{{$request->before_image}}" alt="">
                        </div>
                </section>
              </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
              <div class="col-md-12">
                <section class="widget bg-white post-comments">
                    <div class="widget bg-success mb0 text-center no-radius"><strong>{{ tr('after_service') }}</strong></div>
                        <div class="media">
                            <img style="width:100%;" src="{{$request->after_image}}" alt="">
                        </div>
                </section>
              </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-xs-12">
                <div id="map"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style type="text/css">
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    #map {
        height: 100%;
        min-height: 400px;
    }

    .controls {
        /*margin-top: 10px;*/
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        margin-bottom: 10px;
    }

    #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        /*margin-left: 12px;*/
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 100%;
    }

    #pac-input:focus {
        border-color: #4d90fe;
    }

    #location-search {
        width: 100%;
    }

</style>
@endsection

@section('scripts')
<script>
    var map;
    var serviceLocation = {lat: {{ $request->latitude }}, lng: {{ $request->longitude }}};

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: serviceLocation,
            zoom: 15
        });

        var marker = new google.maps.Marker({
            map: map,
            position: serviceLocation,
            visible: true,
            animation: google.maps.Animation.DROP,
        });

        /*
        var input = document.getElementById('pac-input');

        var button = document.getElementById('location-search');

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        */


        /*

        function search() {
            infowindow.close();
            marker.setVisible(false);
            var place = autocomplete.getPlace();
            // get first autocomplete value *************
            console.log(autocomplete.getPlace());
            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
            }
            marker.setIcon(({
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(35, 35)
            }));
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            var address = '';
            if (place.address_components) {
                address = [
                  (place.address_components[0] && place.address_components[0].short_name || ''),
                  (place.address_components[1] && place.address_components[1].short_name || ''),
                  (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }

            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);
        };

        autocomplete.addListener('place_changed', search);

        button.addEventListener('click', search);

        */
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places&callback=initMap" async defer></script>
@endsection
