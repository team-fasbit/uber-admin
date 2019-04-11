@extends('layouts.admin')

@section('title', tr('map'))

@section('content-header','Drivers availability Stats on Map' )

@section('breadcrumb')
    <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
    <li ><i class="fa fa-map"></i> {{tr('map')}} </li>
    <li class="active"><i class="fa fa-map"></i> Driver availability Stats </li>
@endsection

@section('content')

@include('notification.notify')

<div class="panel">

    <div class="panel-body">
    
<!--         <div class="row">
            <div class="col-md-10">
                <input id="pac-input" class="controls" type="text" placeholder="Enter a location">
            </div>
            <div class="col-md-2">
                <button class="btn btn-success controls" id="location-search">Search</button>
            </div>
        </div> -->
        <div class="row">
            <div class="col-xs-12">
                <div class="map_content">
                    <p class="lead">
                         Use this screen to find all the drivers who are available right now &
                    </p>
                     <p class="lead">
                     -&nbsp; Who are Idle ie. Not ready to take a ride now ( maybe they are having their dinner )
                     </p>
                     <p class="lead">
                     -&nbsp;    Who are ready to accept a ride now
                     </p>
                     <p class="lead">
                        All Driver stats - at your finger tips!
                     </p>
                 </div>
                <div id="map"></div>
                <div id="legend"><h3>{{ tr('providers')}}</h3>
                </div>
                <h6>*Click on a Location Pointer to view Provider Details</h6>
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
        min-height: 500px;
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

    #legend {
        font-family: Arial, sans-serif;
        background: rgba(255,255,255,0.8);
        padding: 10px;
        margin: 10px;
        border: 2px solid #f3f3f3;
    }
    #legend h3 {
        margin-top: 0;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
    }
    #legend img {
        vertical-align: middle;
        margin-bottom: 5px;
    }
</style>
@endsection

@section('scripts')
<script>
    var map;
    var markers = [
        @foreach($Providers as $Provider)
        { provider_id: "{{ $Provider->id }}",name: "{{ $Provider->name }}", lat: {{ $Provider->latitude }}, lng: {{ $Provider->longitude }}, available: {{ $Provider->is_available }} },
        @endforeach
    ];

    var mapIcons = [
        // 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
        '{{ asset('images/map-marker-red.png') }}',
        '{{ asset('images/map-marker-blue.png') }}',
        // 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
    ];
    var mapMarkers = [];
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: -34.397, lng: 150.644},
            zoom: 2,
            minZoom: 1
        });

        /*
        var input = document.getElementById('pac-input');

        var button = document.getElementById('location-search');

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        */

        markers.forEach( function(element, index) {

            var url = "/admin/provider/details/"

            marker = new google.maps.Marker({
                position: {lat: element.lat, lng: element.lng},
                map: map,
                title: element.name,
                icon: mapIcons[element.available],
            });

            mapMarkers.push(marker);

            google.maps.event.addListener(marker, 'click', function() {
                window.location.href = url + element.provider_id;
            });

        });

        var legend = document.getElementById('legend');
        var div = document.createElement('div');
        div.innerHTML = '<img src="' + mapIcons[0] + '"> ' + 'Unavailable';
        legend.appendChild(div);
        var div = document.createElement('div');
        div.innerHTML = '<img src="' + mapIcons[1] + '"> ' + 'Available';
        legend.appendChild(div);
        map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);
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
