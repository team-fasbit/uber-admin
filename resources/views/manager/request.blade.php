@extends('layouts.manager')

@if(isset($name))
  @section('title', tr('view_history'))
@else
  @section('title', tr('requests'))
@endif

@if(isset($name))
  @section('content-header', tr('view_history'))
@else
  @section('content-header', tr('requests'))
@endif


@section('breadcrumb')
    <li><a href="{{route('manager.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    @if(isset($name))
      <li><a href="{{route('manager.users')}}"><i class="fa fa-user"></i> {{tr('users')}}</a></li>
      <li class="active"><i class="fa fa-university"></i> {{tr('view_history')}}</li>
    @else

      <li class="active"><i class="fa fa-university"></i> {{tr('requests')}}</li>
    @endif

@endsection

@section('content')

    @include('notification.notify')



<div class="row">
  <div class="col-xs-12">
    <div class="box box-info">

      <div class="box-body">

      	@if(count($requests) > 0)

          	<table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th class="min">User Name</th>
                  <th class="min">Provider Name</th>
                  <th class="min">DateTime</th>
                  <th>Status</th>
                  <th>Amount</th>
                  <th>Payment Mode</th>
                  <th>Request Type</th>
                  <th>Payment Status</th>
                  <th>Action</th>
                  </tr>
              </thead>
              <tbody>
              @foreach($requests as $index => $requestss)
                @if($requestss->manager_id!=0 || $requestss->manager_id!= '')
                <!--<input type="text" name="user_id" id="user_id" value="{{--$requestss->user_id--}}" style="display:none"/>-->

                <tr>
                    <td>{{$index + 1}}</td>
                    <td>{{$requestss->user_first_name . " " . $requestss->user_last_name}}</td>
                    <td>@if($requestss->confirmed_provider){{$requestss->provider_first_name . " " . $requestss->provider_last_name}} @else - @endif</td>
                    <td>{{$requestss->date}}</td>
                    <td>@if($requestss->status == 0)
                              New
                        @elseif($requestss->status == 1)
                              Waiting
                        @elseif($requestss->status == 2)

                          @if($requestss->provider_status == 0)
                              Provider Not Found
                          @elseif($requestss->provider_status == 1)
                              Provider Accepted
                          @elseif($requestss->provider_status == 2)
                              Provider Started
                          @elseif($requestss->provider_status == 3)
                              Provider Arrived
                          @elseif($requestss->provider_status == 4)
                              Service Started
                          @elseif($requestss->provider_status == 5)
                              Service Completed
                          @elseif($requestss->provider_status == 6)
                              Provider Rated
                          @endif

                          @elseif($requestss->status == 3)

                                Payment Pending
                          @elseif($requestss->status == 4)

                                Request Rating
                          @elseif($requestss->status == 5)

                                Request Completed
                          @elseif($requestss->status == 6)

                                Request Cancelled
                          @elseif($requestss->status == 7)

                                Provider Not Available
                          @endif
                      </td>
                      <td>{{get_currency_value($requestss->amount ? $requestss->amount : 0)}}</td>
                      <td>@if($requestss->manager_id==0 || $requestss->manager_id== ''){{$requestss->payment_mode}}@else {{'cod'}}@endif</td>
                      <td>@if($requestss->manager_id==0 || $requestss->manager_id== ''){{'Normal'}}@else {{'Manager'}}@endif</td>
                      <td>@if($requestss->payment_status==0) <span class="label label-danger">Not Paid</span> @else <span class="label label-success">Paid</span> @endif</td>
                    <td>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                              <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">

                              <li>
                                <a href="{{route('manager.view.request', array('id' => $requestss->id))}}">View Request</a>
                              </li>

                              <li>
                                <a href="{{route('manager.cancel_request', array('id' => $requestss->id))}}">Cancel Request</a>
                              </li>

                              <li>
                                <a data-toggle="modal" onclick="showProviders({{$requestss->id}})" >Re Assign</a>
                              </li>

                            </ul>
                          </div>
                    </td>
                </tr>
                @endif
              @endforeach
              </tbody>
		</table>
	@else
		<h3 class="no-result">{{tr('no_data_found')}}</h3>
	@endif
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="show_providers_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function showProviders(request_id)
 { 
//  $("#show_providers_modal").modal("show");  
// alert(request_id);return;
// if($("#hours_err_msg").val() == 1){
//   alert('please enter valid hours');
//   return;
// }           
            var type = 're_assign';
            var request_id = request_id;
            //var user_id = $("#user_id").val();
            //alert(user_id);
            //var uniq_id = $('#uniq_id').val();
            //var service_id = $('#service_id').val();
            //var total = $('#total').val();
            //var first_name = $('#first_name').val();
            //var last_name = $('#last_name').val();
            //var email = $('#email').val();
            // var s_latitude = $('#s_latitude').val();
            // var s_longitude = $('#s_longitude').val();
            // var d_latitude = $('#d_latitude').val();
            // var d_longitude = $('#d_longitude').val();
            // var s_address = $('#origin-input').val();
            // var d_address = $('#destination-input').val();
            // var request_status_type = $('#request_status_type').val();
            // var hourly_package_id = $('#hourly_package_id').val();
            // var airport_price_id = $('#airport_price_id').val();
         //alert(request_status_type);   

   //var dataString = 'uniq_id='+uniq_id+'&service_id='+service_id+'&total='+total+'&first_name='+first_name+'&last_name='+last_name+'&email='+email+'&s_latitude='+s_latitude+'&s_longitude='+s_longitude+'&d_latitude='+d_latitude+'&d_longitude='+d_longitude+'&s_address='+s_address+'&d_address='+d_address+'&request_status_type='+request_status_type+'&hourly_package_id='+hourly_package_id+'&airport_price_id='+airport_price_id;
      
    //var dataString = 'type='+type+'&request_id='+request_id+'&user_id='+user_id;
    var dataString = 'type='+type+'&request_id='+request_id;


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
             //$('#providers').val(providers);
             trHTML += "<table class='table table-striped' style='margin-bottom:0;'>";
             $.each(providers, function (j, provider) {
              
              var no = ++sl_no;
              trHTML += "<tr><td>  " + no + "  </td><td>  </td><td>  </td><td>  " + provider + "  </td><td><button type='button' class='btn btn-success pull-right' onclick = 'manual_create_request("+request_id+","+j+");'>Send Request</button></td></tr>";
            });
            trHTML += "</table>";
          }
        });
        $('#here_table').append(trHTML);      
        $("#show_providers_modal").modal('show');
     }
     else{
      $('#prov_message').html(json.error);
      $('#here_table').html("  ");
      $("#show_providers_modal").modal('show');
     }
   }

   });
 }

 function manual_create_request(request_id,provider_id){
  //alert(provider_id);return;
               var type = 're_assign';
               var request_id = request_id;
               var provider_id = provider_id;
   //          var uniq_id = $('#uniq_id').val();
   //          var service_id = $('#service_id').val();
   //          var total = $('#total').val();
   //          var first_name = $('#first_name').val();
   //          var last_name = $('#last_name').val();
   //          var email = $('#email').val();
   //          var s_latitude = $('#s_latitude').val();
   //          var s_longitude = $('#s_longitude').val();
   //          var d_latitude = $('#d_latitude').val();
   //          var d_longitude = $('#d_longitude').val();
   //          var s_address = $('#origin-input').val();
   //          var d_address = $('#destination-input').val();
   //          var request_status_type = $("#request_status_type").val();
   //          var hourly_package_id = $('#hourly_package_id').val();
   //          var airport_price_id = $('#airport_price_id').val();
   // var dataString = 'uniq_id='+uniq_id+'&service_id='+service_id+'&total='+total+'&first_name='+first_name+'&last_name='+last_name+'&email='+email+'&s_latitude='+s_latitude+'&s_longitude='+s_longitude+'&d_latitude='+d_latitude+'&d_longitude='+d_longitude+'&s_address='+s_address+'&d_address='+d_address+'&provider_id='+provider_id+'&request_status_type='+request_status_type+'&hourly_package_id='+hourly_package_id+'&airport_price_id='+airport_price_id;
     var dataString = 'type='+type+'&request_id='+request_id+'&provider_id='+provider_id;
     $.ajax({
     type: "POST",
     url : "{{route('manager.manual_create_request')}}",
     data: dataString, 
     success : function(data){
       
        var json = $.parseJSON(data);
       console.log(json);
        if(json.success == true){
          alert('Request sent successfully!!');
          $('#show_providers_modal').modal('hide');
         window.location.href = "{{route('manager.create_request')}}";
         
        }
        if(json.success == false){
          alert(json.error);
          $('#show_providers_modal').modal('show');
        }
        
        //console.log(json);
      }
   });
 }

 </script>
@endsection
