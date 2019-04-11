@extends('layouts.manager')

@if(isset($name))
  @section('title', tr('edit_service_type'))
@else
  @section('title', 'Proceed Request')
@endif


@section('content-header', tr('proceed_request'))

@section('breadcrumb')
    <li><a href="{{route('manager.dashboard')}}"><i class="fa fa-dashboard"></i>Home</a></li>
    <!--<li><a href="{{route('admin.service.types')}}"><i class="fa fa-user"></i> {{tr('service_types')}}</a></li>-->
    <li class="active">{{tr('proceed_request')}}</li>
@endsection

@section('content')

@include('notification.notify')

<style>
  @media (min-width: 768px)
  {
    .modal-dialog {
    width: 600px;
    margin: 30px auto;
  }
  }
   #map {
        height: 400px;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #origin-input,
      #destination-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 200px;
      }

      #origin-input:focus,
      #destination-input:focus {
        border-color: #4d90fe;
      }

      #mode-selector {
        color: #fff;
        background-color: #4d90fe;
        margin-left: 12px;
        padding: 5px 11px 0px 11px;
      }

      #mode-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }
      #here_table td,th{
        padding:10px;
      }
      #mode-selector{
        left: 424px !important;
      }
      /*#destination-input {
        left: 433px !important;
      }*/
</style>
<div class="row">

  <div class="col-md-12">

      <div class="box box-info">
          <div class="panel-heading border">

          </div>
           
              
          <form class="form-horizontal bordered-group" action="" method="" enctype="multipart/form-data" role="form" onsubmit="showProviders();return false;" style="padding-bottom: 6px;">
            <div class="form-group">
              <label class="col-sm-2 control-label">Caller Id</label>
              <div class="col-sm-8">
                <input placeholder="uniq_id" type="text" name="uniq_id" value="{{isset($data['uniq_id']) ? $data['uniq_id'] : ''}}" required class="form-control" disabled>
              </div>
               <div class="col-sm-2">
                <button type="button" class="btn btn-primary" onclick="show_dateInputBox();">Request Later</button>
              </div>
            </div>
             
            
            <div class="form-group" id="requested_time_div" style="display:none">
               <label class="col-sm-2 control-label">select Date</label>
                <div class="col-lg-8">
                  <input type="text" id="date-format" name="requested_time" class="form-control" placeholder="Select Date">
                </div>
            </div>
<!-- <input type="button" value="request_latr" onclick="sel_request_latr();" />
<input type="text" id="requested_time" value="2017-10-10 15:12:00" onclick="sel_request_latr();" /> -->

            <!--<div class="form-group">
              <label class="col-sm-2 control-label">Payment Mode</label>
              <div class="col-sm-8">
                
                <select id="payment_mode"  name="payment_mode" required class="form-control" >
                  <option value="" >select payment mode</option>
                  @foreach($payment_modes as $key=>$payment_mode)
                  <option value="{{$payment_mode}}">{{$payment_mode}}</option>
                  @endforeach
                </select>
                </div>
            </div>-->


            <div class="form-group">
              <label class="col-sm-2 control-label">Service Type</label>
              <div class="col-sm-8">
                
                <select id="service_id"  name="service_type" required class="form-control" >
                  <option value="select service type" >Select the Vehicle Type</option>
                  @foreach($service_types as $service_type)
                  <option value="{{$service_type->id}}">{{$service_type->name}}</option>
                  @endforeach
                </select>
                </div>

            </div>

            <!-- values of request_status_types as defined in controller -->
            <!-- $request_status_types = array(1=>'NORMAL_REQUEST', 2=>'HOURLY_PACKAGE', 3=>'AIRPORT_PACKAGE');-->
            <div class="form-group">
              <label class="col-sm-2 control-label">Request Status Type</label>
              <div class="col-sm-8">
                <select id="request_status_type"  name="request_status_type"  class="form-control" required onchange="sel_req_stats_typ(this)">
                  <option value="select service type" >Select type of request</option>
                  @foreach($request_status_types as $key=>$request_status_type)
                  <option value="{{$key}}">{{$request_status_type}}</option>
                  @endforeach
                </select>
              </div>
            </div>
              
             <div class="form-group" id="hours-div" style="display:none">
              <label class="col-sm-2 control-label">Hours</label>
              <div class="col-sm-8">
                <input placeholder="Enter no of hours.." type="text" name="hours" id="hours" value="" class="form-control" onblur="hourly_package_fare();">
              </div>
              <span id="hours_err_msg" value="0" style="color:red;"></span>
            </div> 

            <div class="form-group" id="airport-div" style="display:none">
              <!--<label class="col-sm-2 control-label">Hours</label>-->
              <div class="col-lg-offset-2 col-lg-3" >
                <input  type="radio" name="to_or_from" id="to_airport" value="to_airport" onclick="show_airport_n_location_details(this);">
                <label for="to_airport" style="margin: -5px 10px 0 5px;">To Airport?</label>

                <input  type="radio" name="to_or_from" id="frm_airport" value="frm_airport" onclick="show_airport_n_location_details(this);">
                <label for="frm_airport" style="margin: -5px 10px 0 5px;">From Airport?</label>
              </div>
            </div>


            <div class="form-group" id="airport_details_main" style="display:none">
            <label class="col-sm-2 control-label">Airport Details</label>
              <div class="col-sm-8">
                <div id="airport_details"></div>
              </div>
            <span id="airport_err_msg" value="0" style="color:red;"></span>
            </div>

            <div class="form-group" id="location_details_main" style="display:none">
            <label class="col-sm-2 control-label" id="html_toORfrom" value="">Location Details</label>
              <div class="col-sm-8">
                <div  id="location_details"></div>
              </div>
            <span id="location_err_msg" value="0" style="color:red;"></span>
            </div>
            
            
            <!--hidden fields-->
            <input type="hidden" name="first_name" id="first_name" value="@if(isset($data['first_name'])){{$data['first_name']}}@endif" />

            <input type="hidden" name="last_name" id="last_name" value="@if(isset($data['last_name'])){{$data['last_name']}}@endif" />

            <input type="hidden" name="email" id="email" value="@if(isset($data['email'])){{$data['email']}}@endif" />

            <input type="hidden" name="uniq_id" id="uniq_id" value="@if(isset($data['uniq_id'])){{$data['uniq_id']}}@endif" />

            <!--<input type="hidden" name="request_status_type" id="request_status_type" value=""/>-->

            <input name="s_latitude" id="s_latitude" class="controls" type="hidden" value="">
            <input name="s_longitude" id="s_longitude" class="controls" type="hidden" value="">
            <input name="d_latitude" id="d_latitude" class="controls" type="hidden" value="">
            <input name="d_longitude" id="d_longitude" class="controls" type="hidden" value>

            
            <!-- hourly and airport package id's -->
            <input name="hourly_package_id" id="hourly_package_id" class="controls" type="hidden" value="">
            <input name="airport_price_id" id="airport_price_id" class="controls" type="hidden" value="">

            <!--<input type="hidden" id="is_it_toORfrom" name="is_it_toORfrom"
            value="" class="controls"/>-->
            

            <!--user to enter source and dest locatns-->
            <input id="origin-input" name="origin_input"  class="controls" type="text"
                placeholder="Enter an origin location" >

            <input id="destination-input" name="destination_input"  class="controls" type="text"
                placeholder="Enter a destination location" >

            <div id="mode-selector" class="controls">
              <input type="radio" name="type" id="changemode-walking">
              <label for="changemode-walking">Walking</label>

              <input type="radio" name="type" id="changemode-transit">
              <label for="changemode-transit">Transit</label>

              <input type="radio" name="type" id="changemode-driving" checked="checked">
              <label for="changemode-driving">Driving</label>
            </div>

            <!-- Map -->
             <div id="map"></div>

             </div>
            </div>

            <!--<input type="text" value="" id="number_tolls" name="number_tolls" class="form-control" style="display:none" />-->

            <div class="form-group col-lg-12" style="display:none" id="number_tolls_main" >
              <label class="col-sm-2 control-label">No of Toll Gates</label>
              <div class="col-sm-8" style="padding: 0 6px;">
                <input type="text" value="" id="number_tolls" name="number_tolls" class="form-control" disabled/>
              </div>
            </div>

            <div class="form-group col-lg-12">
              <label class="col-sm-2 control-label">Fare Estimate</label>
              <div class="col-sm-8" style="padding: 0 6px;">
                <input placeholder="total" id="total" type="text" name="total" value="" class="form-control">
              </div>
            </div>



            <div class="box-footer" id="find_providers" style="display:block">
                <button type="submit" class="btn btn-success pull-right" >Find Providers</button>
            </div>

            <div class="box-footer" id="schedule" style="display:none">
                <button type="button" class="btn btn-success pull-right" onclick="request_later()">Schedule</button>
            </div>

          </form>
          
      </div>

  </div>

</div>

<!-- Modal if provider is available-->
<div class="modal fade" id="pro_unavai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Provider List</h4>
      </div>
      <div class="modal-body">
        <p id="prov_message">Message</p>
        <div class="table-responsive">
          <div id="here_table"></div>
          <!--<input type="text" id="prov_message"  name="prov_message" value="" >-->
                
              
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal if no provider is available-->
<!--<div class="modal fade" id="pro_avai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Provider List</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <div id="here_table"></div>
         
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Provider Name</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                   
                    <td><p>Mathew</p></td>
                    <td>
                      <button type="button" class="btn btn-success">Send request</button>
                    </td>
                  </tr>
                   <tr>
                   
                    <td><p>Mathew</p></td>
                    <td>
                      <button type="button" class="btn btn-success">Send request</button>
                    </td>
                  </tr>
                   <tr>
                   
                    <td><p>Mathew</p></td>
                    <td>
                      <button type="button" class="btn btn-success">Send request</button>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>-->

<script type="text/javascript" >

function sel_req_stats_typ(obj) {


    var selectBox = obj;
    var request_status_type = selectBox.options[selectBox.selectedIndex].value;
    var hours = document.getElementById("hours-div");
    var origin_input = document.getElementById("origin-input");
    var destination_input = document.getElementById("destination-input");
    var mode_selector = document.getElementById("mode-selector");
    var airport_div = document.getElementById("airport-div");
    var map = document.getElementById("map");
    var airport_details_main = document.getElementById("airport_details_main");
    var location_details_main = document.getElementById("location_details_main");
    var number_tolls_main = document.getElementById("number_tolls_main");
    $('#total').val('');
    $('#hours').val('');
    if(request_status_type === '2'){      //HOURLY_PACKAGE
        hours.style.display = "block";
        origin_input.value = "";
        destination_input.style.display = "none";
        mode_selector.style.display = "none";
        airport_div.style.display = "none"; //radio buttons
        map.style.display = "block"; 
        airport_details_main.style.display = "none"; 
        location_details_main.style.display = "none";
        number_tolls_main.style.display = "none";      
    }
    else if(request_status_type === '3'){  //AIRPORT_PACKAGE
        airport_div.style.display = "block";
        hours.style.display = "none";
        // origin_input.value = "";
        // destination_input.style.display = "none";
        // mode_selector.style.display = "none";
        map.style.display = "none"; 
           
    }
    else{
        hours.style.display = "none";
        destination_input.style.display = "block";
        mode_selector.style.display = "block";
        airport_div.style.display = "none";
        map.style.display = "block";
        airport_details_main.style.display = "none"; 
        location_details_main.style.display = "none";
        number_tolls_main.style.display = "none";     
    }
}

function show_dateInputBox() {
  var requested_time_div = document.getElementById("requested_time_div");
  if(requested_time_div.style.display === 'none'){
    $("#find_providers").hide();
    $("#schedule").show();
    $("#requested_time_div").show();
  }else{
    $("#find_providers").show();
    $("#schedule").hide();
    $("#requested_time_div").hide();
  }
  
}

// function sel_request_latr(){
//   $("#find_providers").hide();
//   $("#schedule").show();
// }

function request_later() {
  //alert($('#uniq_id').val());return;
            var requested_time = $("#date-format").val();
            var uniq_id = $('#uniq_id').val();
            var service_id = $('#service_id').val();
            var total = $('#total').val();
            var first_name = $('#first_name').val();
            var last_name = $('#last_name').val();
            var email = $('#email').val();
            var s_latitude = $('#s_latitude').val();
            var s_longitude = $('#s_longitude').val();
            var d_latitude = $('#d_latitude').val();
            var d_longitude = $('#d_longitude').val();
            var s_address = $('#origin-input').val();
            var d_address = $('#destination-input').val();
            var request_status_type = $("#request_status_type").val();
            var hourly_package_id = $('#hourly_package_id').val();
            var airport_price_id = $('#airport_price_id').val();
   var dataString = 'uniq_id='+uniq_id+'&service_id='+service_id+'&total='+total+'&first_name='+first_name+'&last_name='+last_name+'&email='+email+'&s_latitude='+s_latitude+'&s_longitude='+s_longitude+'&d_latitude='+d_latitude+'&d_longitude='+d_longitude+'&s_address='+s_address+'&d_address='+d_address+'&request_status_type='+request_status_type+'&hourly_package_id='+hourly_package_id+'&airport_price_id='+airport_price_id+'&requested_time='+requested_time;
   //console.log(dataString);return;
     $.ajax({
     type: "POST",
     url : "{{route('manager.request_later')}}",
     data: dataString, 
     success : function(data){       
        var json = $.parseJSON(data);
        console.log(json);
        if(json.success == true){
          //alert(json.success);
          alert('Scheduled successfully!!');
          window.location.href = "{{route('manager.create_request')}}";
        }
        if(json.success == false){
          alert(json.error);
        }
        
        //console.log(json);
      }
   });

  // body...
}

function show_airport_n_location_details(to_or_frm) {

  $('#total').val(''); //initially making total as empty
  
  //for getting airport_details dropdown
  var airport_details_main = document.getElementById("airport_details_main");
  var location_details_main = document.getElementById("location_details_main");
  $.ajax({
   type: "GET",
   url : "{{route('manager.airport_details')}}",
   data: "",
   success : function(data){
    var json = $.parseJSON(data);
    //console.log(json);
    console.log(json);
     if (json.success == "true" || json.success == 1 && json.success != "") {
        $("#airport_err_msg").html("");
        $("#airport_err_msg").val(0);
        $.each(json, function (i, item) {
          if(i == "airport_details"){
            var airport_details = item;
            var airport_select ="";
  airport_select = "<select id='airport_detail'  name='airport_detail' required class='form-control' onchange='airport_package_fare()'>";
            airport_select += "<option value=''>Select Airport</option>";
            $.each(airport_details, function (j, airport_detail) {
              airport_select += "<option value="+airport_detail.id+">"+airport_detail.name+"</option>";
            });
            airport_select += "</select>";
            airport_details_main.style.display = "block";  
            $('#airport_details').html(airport_select);
          }
        });
      }else if(json.success == "false" || json.success == 0 && json.success == ""){
            $("#airport_err_msg").html(json.error);
            $("#airport_err_msg").val(1);
            // alert($("#hours_err_msg").val());
      }
   }

   });


//for getting location_details dropdown
$.ajax({
   type: "GET",
   url : "{{route('manager.location_details')}}",
   data: "service_type="+service_id,
   success : function(data){
    var json = $.parseJSON(data);
    //console.log(json);
    console.log(json);
     if (json.success == "true" || json.success == 1 && json.success != "") {
        $("#location_err_msg").html("");
        $("#location_err_msg").val(0);
          $.each(json, function (i, item) {
          if(i == "location_details"){
            var location_details = item;
            var location_select ="";
location_select = "<select id='location_detail'  name='location_detail' required class='form-control' onchange='airport_package_fare()'>";
            location_select += "<option value=''>Select Location</option>";
            $.each(location_details, function (j, location_detail) {
              location_select += "<option value="+location_detail.id+">"+location_detail.name+"</option>";
            });
            location_select += "</select>";
            location_details_main.style.display = "block";  
            $('#location_details').html(location_select);
          }
        });
     }else if(json.success == "false" || json.success == 0 && json.success == ""){
            $("#location_err_msg").html(json.error);
            $("#location_err_msg").val(1);
            // alert($("#hours_err_msg").val());
      }

   }

   });


  //changing innerHTML based on to_or_from airport radio button select 
  var to_or_from = to_or_frm.value;
  if(to_or_from == "to_airport"){
      $("#html_toORfrom").html("Select Source Location");
      $("#html_toORfrom").val("to_airport");
  }
  else if(to_or_from == "frm_airport"){
      $("#html_toORfrom").html("Select Destination Location");
      $("#html_toORfrom").val("frm_airport");
  }

}

function airport_package_fare() {
  $("#hourly_package_id").val('');
  $('#total').val('');
  $('#origin-input').val('');
  $('#destination-input').val('');

  var airport_id = $("#airport_detail").val();
  var location_id = $("#location_detail").val();
  var service_id = $('#service_id').val();
  // console.log("airport_id :"+airport_id);
  // console.log("location_id :"+location_id);
  // console.log("service_id :"+service_id);
  if(airport_id == '' || airport_id == null){
    $("#airport_err_msg").html("please select airport details");
  }
  else if(airport_id != '' || airport_id != null){
    $("#airport_err_msg").html("");
  }

  if(location_id == '' || location_id == null){   
    $("#location_err_msg").html("please select location details");
  }else if(location_id != '' || location_id != null){
    $("#location_err_msg").html("");
  }

  if(airport_id != '' && location_id != ''){
    var dataString = 'airport_details_id='+airport_id+'&location_details_id='+location_id+'&service_type='+service_id;

     $.ajax({
     type: "POST",
     url : "{{route('manager.airport_package_fare')}}",
     data: dataString,
     success : function(data){
      var json = $.parseJSON(data);
      console.log(json);
        if (json.success == "true" || json.success == 1 && json.success != "") {
              $("#location_err_msg").html(""); //showing invalid package err msg in location_err_msg span itself,so as not to create another err msg span
              var is_it_toORfrom = $("#html_toORfrom").val();
          $.each(json, function (i, item) {
            if(i == "airport_price_details"){
              var airport_price_details = item;
              $.each(airport_price_details, function (j, airport_price_detail) {
                if(j == 'id'){
                  var airport_price_id = airport_price_detail;
                  $("#airport_price_id").val(airport_price_id);
                }
                if(j == 'price'){
                  var price = airport_price_detail;
                  $('#total').val(price);
                }
                if(j == 'number_tolls'){
                  var number_tolls = airport_price_detail;
                  $('#number_tolls_main').show();
                  $('#number_tolls').val(number_tolls);
                }
              });
            }
            if(is_it_toORfrom == "to_airport"){
              if(i == "airport_lat_long"){
                $('#d_latitude').val(item.latitude);
                $('#d_longitude').val(item.longitude);
                $('#destination-input').val(item.name);                
              }
              if(i == "location_lat_long"){
                $('#s_latitude').val(item.latitude);
                $('#s_longitude').val(item.longitude);
                $('#origin-input').val(item.name);
              }
            }
            else if(is_it_toORfrom == "frm_airport"){
                if(i == "airport_lat_long"){
                $('#s_latitude').val(item.latitude);
                $('#s_longitude').val(item.longitude);
                $('#origin-input').val(item.name);
              }
              if(i == "location_lat_long"){
                $('#d_latitude').val(item.latitude);
                $('#d_longitude').val(item.longitude);
                $('#destination-input').val(item.name); 
              }
            }
          });
        }else if(json.success == "false" || json.success == 0 && json.success == ""){
              $('#number_tolls_main').hide();
              $('#number_tolls').val('');
              $("#location_err_msg").html("package is unavailable"); //showing invalid package err msg in location_err_msg span itself,so as not to create another err msg span
            }

      }

    });
  }

}

function hourly_package_fare(){
  $("#airport_price_id").val('');
  //$('#total').val('');
        var entered_hrs = $('#hours').val();
        var service_id = $('#service_id').val();
        var dataString = 'service_type='+service_id+'&number_hours='+entered_hrs;
         $.ajax({
         type: "POST",
         url : "{{route('manager.hourly_package_fare')}}",
         data: dataString,
         success : function(data){          
         var json = $.parseJSON(data);
          if (json.success == "true" || json.success == 1 && json.success != "") {
            $("#hours_err_msg").html("");
            $("#hours_err_msg").val(0);
              $.each(json, function (i, item) {
                if(i == "hourly_package_details"){
                  var hourly_package_details = $.parseJSON(item);
                  $.each(hourly_package_details, function (j, hourly_package_detail) {
                    if(j == "price"){
                      var price = $.parseJSON(hourly_package_detail);
                      if($('#origin-input').val() == "" || $("#hours_err_msg").val() == 1){
                        $('#hourly_package_id').val('');
                        $('#total').val(' ');
                      }
                      else{
                        $('#total').val(price);
                      }    
                      
                    }
                    if($('#total').val() != ""){
                      if(j == "id"){
                        var hourly_package_id = $.parseJSON(hourly_package_detail);
                        $('#hourly_package_id').val(hourly_package_id);
                      }
                    }

                  });
                }


              });
               
           }
          else if(json.success == "false" || json.success == 0 && json.success == ""){
            $("#hours_err_msg").html("Service is unavailable for the selected hours");
            $("#hours_err_msg").val(1);
            // alert($("#hours_err_msg").val());
          }
        }
      });
}

function showEstimatedFare(distance,time)
 {  
   var service_id = $('#service_id').val();
   var s_latitude = $('#s_latitude').val();
    var s_longitude = $('#s_longitude').val();
    var d_latitude = $('#d_latitude').val();
    var d_longitude = $('#d_longitude').val();

    var dataString = 'service_id='+service_id+'&s_latitude='+s_latitude+'&s_longitude='+s_longitude+'&d_latitude='+d_latitude+'&d_longitude='+d_longitude+'&distance='+distance+'&time='+time;

   $.ajax({
   type: "POST",
   url : "{{route('manager.fare_calculator')}}",
   data: dataString,
   success : function(data){
    //console.log(data);
     $('#total').val(data);
   }

   });
 }

 function showProviders()
 {   

    if($("#hours_err_msg").val() == 1){
      alert('please enter valid hours');
      return;
    }
    var uniq_id = $('#uniq_id').val();
    var service_id = $('#service_id').val();
    var total = $('#total').val();
    var first_name = $('#first_name').val();
    var last_name = $('#last_name').val();
    var email = $('#email').val();
    var s_latitude = $('#s_latitude').val();
    var s_longitude = $('#s_longitude').val();
    var d_latitude = $('#d_latitude').val();
    var d_longitude = $('#d_longitude').val();
    var s_address = $('#origin-input').val();
    var d_address = $('#destination-input').val();
    var request_status_type = $('#request_status_type').val();
    var hourly_package_id = $('#hourly_package_id').val();
    var airport_price_id = $('#airport_price_id').val();
  //alert(request_status_type);   

   var dataString = 'uniq_id='+uniq_id+'&service_id='+service_id+'&total='+total+'&first_name='+first_name+'&last_name='+last_name+'&email='+email+'&s_latitude='+s_latitude+'&s_longitude='+s_longitude+'&d_latitude='+d_latitude+'&d_longitude='+d_longitude+'&s_address='+s_address+'&d_address='+d_address+'&request_status_type='+request_status_type+'&hourly_package_id='+hourly_package_id+'&airport_price_id='+airport_price_id;
           
   $.ajax({
   type: "POST",
   url : "{{route('manager.find_providers')}}",
   data: dataString, 
   success : function(data){
     //alert(data);
      var json = $.parseJSON(data);
      console.log(json);
     $('#here_table').html("  ");
     if (json.success == "true" || json.success == 1 && json.success != "") {
        $('#prov_message').html("Provider Details :");  
        var trHTML = '';
        var sl_no = 0;
        $.each(json, function (i, item) {

          // if (i == "request_status_type") {
          //   var request_status_type = item;
          //   $("#request_status_type").val(request_status_type);
          // };
          if(i == "providers"){
             var providers = $.parseJSON(item);
             $('#providers').val(providers);
             $.each(providers, function (j, provider) {
              
              var no = ++sl_no;
              trHTML += "<tr><td>  " + no + "  </td><td>  </td><td>  </td><td>  " + provider + "  </td><td><button type='button' class='btn btn-success' onclick = 'manual_create_request("+j+");'>Send Request</button></td></tr>";
            });
          }
        });
        $('#here_table').append(trHTML);      
        $('#pro_unavai').modal('show');
     }
     else{
      $('#prov_message').html(json.error);
      $('#here_table').html("  ");
      $('#pro_unavai').modal('show');
     }
   }

   });
 }

 function manual_create_request(provider_id){
            var uniq_id = $('#uniq_id').val();
            var service_id = $('#service_id').val();
            var total = $('#total').val();
            var first_name = $('#first_name').val();
            var last_name = $('#last_name').val();
            var email = $('#email').val();
            var s_latitude = $('#s_latitude').val();
            var s_longitude = $('#s_longitude').val();
            var d_latitude = $('#d_latitude').val();
            var d_longitude = $('#d_longitude').val();
            var s_address = $('#origin-input').val();
            var d_address = $('#destination-input').val();
            var request_status_type = $("#request_status_type").val();
            var hourly_package_id = $('#hourly_package_id').val();
            var airport_price_id = $('#airport_price_id').val();
   var dataString = 'uniq_id='+uniq_id+'&service_id='+service_id+'&total='+total+'&first_name='+first_name+'&last_name='+last_name+'&email='+email+'&s_latitude='+s_latitude+'&s_longitude='+s_longitude+'&d_latitude='+d_latitude+'&d_longitude='+d_longitude+'&s_address='+s_address+'&d_address='+d_address+'&provider_id='+provider_id+'&request_status_type='+request_status_type+'&hourly_package_id='+hourly_package_id+'&airport_price_id='+airport_price_id;
     $.ajax({
     type: "POST",
     url : "{{route('manager.manual_create_request')}}",
     data: dataString, 
     success : function(data){
       
       
        var json = $.parseJSON(data);
        if(json.success == true){
          alert('Request sent successfully!!');
          $('#pro_unavai').modal('hide');
         window.location.href = "{{route('manager.create_request')}}";
         
        }
        if(json.success == false){
          alert(json.error);
          $('#pro_unavai').modal('show');
        }
        
        console.log(json);
      }
   });
 }

</script>
 <script>
      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
//request_status_type = false;
var globalMap;
      function initMap() {
        var map = globalMap = new google.maps.Map(document.getElementById('map'), {
          mapTypeControl: false,
          center: {lat: -33.8688, lng: 151.2195},
          zoom: 13
        });

        new AutocompleteDirectionsHandler(map);
      }

       /**
        * @constructor
       */
      function AutocompleteDirectionsHandler(map) {
        this.map = map;
        this.originPlaceId = null;
        this.destinationPlaceId = null;
        this.travelMode = 'DRIVING';
        var originInput = document.getElementById('origin-input');
        var destinationInput = document.getElementById('destination-input');
        var modeSelector = document.getElementById('mode-selector');
        this.directionsService = new google.maps.DirectionsService;
        this.directionsDisplay = new google.maps.DirectionsRenderer;
        this.directionsDisplay.setMap(map);

        var originAutocomplete = new google.maps.places.Autocomplete(
            originInput, {places: true});
        var destinationAutocomplete = new google.maps.places.Autocomplete(
            destinationInput, {places: true});

        this.setupClickListener('changemode-walking', 'WALKING');
        this.setupClickListener('changemode-transit', 'TRANSIT');
        this.setupClickListener('changemode-driving', 'DRIVING');

        this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
        this.setupPlaceChangedListener(destinationAutocomplete, 'DEST');

        this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(originInput);
        this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(destinationInput);
        this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(modeSelector);
      }

      // Sets a listener on a radio button to change the filter type on Places
      // Autocomplete.
      AutocompleteDirectionsHandler.prototype.setupClickListener = function(id, mode) {
        var radioButton = document.getElementById(id);
        var me = this;
        radioButton.addEventListener('click', function() {
          me.travelMode = mode;
          me.route();
        });
      };

      AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function(autocomplete, mode) {
        var me = this;
        autocomplete.bindTo('bounds', this.map);
        autocomplete.addListener('place_changed', function() {  
          var place = autocomplete.getPlace();

          if (!place.place_id) {
            window.alert("Please select an option from the dropdown list.");
            return;
          }
//google.maps.event.trigger(globalMap, 'resize');
          var location = autocomplete.getPlace().geometry.location;

            //user to enter origin location if hourly package is choosen
              if(document.getElementById("request_status_type").value == 2){
                document.getElementById("s_latitude").value = location.lat();
                document.getElementById('s_longitude').value = location.lng();
                document.getElementById('d_latitude').value = " ";
                document.getElementById('d_longitude').value = " ";
                document.getElementById('destination-input').value = " ";


                var LatLng = new google.maps.LatLng(location.lat(), location.lng());
                    var mapOptions = {
                        //map: this.map
                        center: LatLng,
                        zoom: 13,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };
                    //var map = new google.maps.Map(document.getElementById("map"), mapOptions);
                    var marker = new google.maps.Marker({
                        map: globalMap,
                        position: {lat:location.lat(), lng:location.lng()},
                        visible: true,
                        animation: google.maps.Animation.DROP,
                    });
                    globalMap.panTo({lat:location.lat(), lng:location.lng()});

                    hourly_package_fare(); // in this functn hourly_package_fare is calculated
              }
          else{
              if (mode === 'ORIG') {
                me.originPlaceId = place.place_id;
                document.getElementById("s_latitude").value = location.lat();
                document.getElementById('s_longitude').value = location.lng();

              } else {
                me.destinationPlaceId = place.place_id;
                document.getElementById('d_latitude').value = location.lat();
                document.getElementById('d_longitude').value = location.lng();
              }
              me.route();
            
          }

          });
      };

      AutocompleteDirectionsHandler.prototype.route = function() {
        // var s_latitude = document.getElementById("s_latitude").value;
        // var s_longitude = document.getElementById("s_longitude").value;
        // //alert('s_latitude'+s_latitude);


        if (!this.originPlaceId || !this.destinationPlaceId) {
          return;
        }

        
        var me = this;
        // var lat = place.geometry.location.lat();
        // alert('this.originPlaceId'+lat);
        this.directionsService.route({
          origin: {'placeId': this.originPlaceId},
          destination: {'placeId': this.destinationPlaceId},
          travelMode: this.travelMode
        }, function(response, status) {
          if (status === 'OK') {
            console.log(response);
            me.directionsDisplay.setDirections(response);
            var distance = response.routes[0].legs[0].distance.value;//in meters
            var time = response.routes[0].legs[0].duration.value;//in secs
            showEstimatedFare(distance,time);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });


      };
  </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBYOBFt801Mn0EWSTzKOlpFmQE0TJ3jGnU&libraries=places&callback=initMap"
        async defer></script>
      <script type="text/javascript">
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (p) {
        var LatLng = new google.maps.LatLng(p.coords.latitude, p.coords.longitude);
        var mapOptions = {
            center: LatLng,
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map"), mapOptions);
        var marker = new google.maps.Marker({
            position: LatLng,
            map: map,
            title: "<div style = 'height:60px;width:200px'><b>Your location:</b><br />Latitude: " + p.coords.latitude + "<br />Longitude: " + p.coords.longitude
        });
        google.maps.event.addListener(marker, "click", function (e) {
            var infoWindow = new google.maps.InfoWindow();
            infoWindow.setContent(marker.title);
            infoWindow.open(map, marker);
        });
    });
} else {
    alert('Geo Location feature is not supported in this browser.');
}
</script>
 

@endsection
