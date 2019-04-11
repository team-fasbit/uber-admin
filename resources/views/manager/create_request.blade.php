@extends('layouts.manager')

@if(isset($name))
  @section('title', tr('edit_service_type'))
@else
  @section('title', 'Create Request')
@endif


@section('content-header', tr('create_request'))

@section('breadcrumb')
    <li><a href="{{route('manager.dashboard')}}"><i class="fa fa-dashboard"></i>Home</a></li>
    <!--<li><a href="{{route('admin.service.types')}}"><i class="fa fa-user"></i> {{tr('service_types')}}</a></li>-->
    <li class="active">{{tr('create_request')}}</li>
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
           <!-- <p class="lead ">
             * Use this screen to Add a Vehicle type Ex: Sedan, Hatch back, Saloon
            </p>
             <p class="lead ">
            * The vehicle type you add here, will appear on the Passenger mobile app <a data-toggle="modal" href="#myModal">( Refer the screen shot present below the form )</a>
            </p>-->
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

          <form class="form-horizontal bordered-group" action="{{route('manager.create_request.post')}}" method="POST" enctype="multipart/form-data" role="form">
            <div class="form-group">
              <label class="col-sm-2 control-label">First Name</label>
              <div class="col-sm-8">
                <input placeholder="first name.." type="text" name="first_name" value="" required class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Last Name</label>
              <div class="col-sm-8">
                <input placeholder="last name.." type="text" name="last_name" value="" required class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Contact No</label>
              <div class="col-sm-8">
                <input placeholder="contact no.." type="text" name="mobile" value="" required class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Email Id</label>
              <div class="col-sm-8">
                <input placeholder="email id.." type="email" name="email" value="" required class="form-control">
              </div>
            </div>
            

            <div class="box-footer">
                <!--<a href="{{-- route('admin.service.types' ) --}}" class="btn btn-danger">{{tr('cancel')}}</a>-->
                <button type="submit" class="btn btn-success pull-right">{{tr('submit')}}</button>
            </div>

          </form>

      </div>

  </div>

</div>



@endsection
