<?php 
  use App\Admin; 
  $is_permitted_user = Admin::find(Auth::guard('admin')->user()->id);
?>
@extends('layouts.admin')

@section('title', tr('service_types'))

@section('content-header', 'Vehicle type')

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li class="active"><i class="fa fa-user"></i> {{tr('service_types')}}</li>
@endsection

@section('content')

	@include('notification.notify')


<div class="row">
      <div class="col-xs-12">
        <div class="box box-info">
          <div class="box-body">
              <div class="box-header">
        <div class="map_content">
            <p class="lead ">
              Use this screen to manage all the Vehicle Type's entered in the system
            </p>
             <p class="lead ">
           Note: Deleting a Vehicle type will also remove it from the Passenger app.
            </p>
          </div>
          </div>
            @if(count($services) > 0)

          <table id="example1" class="table table-bordered table-striped">

            <thead>
              <tr>
                <th>{{ tr('id') }}</th>
                <th class="min">{{ tr('service_types') }}</th>
                <th class="min">No of Seats</th>
                <th class="min">Base Fare</th>
                <th class="min">Min. Fare</th>
                <th class="min">{{ tr('status') }}</th>
                <th>{{ tr('action') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($services as $index => $service)
            <tr>
                <td>{{$index + 1 }}</td>
                <td>{{$service->name}}</td>
                <td>{{$service->number_seat}}</td>
                <td>{{$service->min_fare}}</td>
                <td>{{$service->min_fare}}</td>
                <td>@if($service->status == 1) {{ tr('default') }} @else NA @endif</td>
                <td class="btn-left">
                  <?php if($is_permitted_user->vehicle_types !='' && $is_permitted_user->vehicle_types !=0){ ?>
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">{{ tr('action') }}
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <?php if($is_permitted_user->vehicle_types !='' && $is_permitted_user->vehicle_types !=0 && in_array(EDIT, explode(',', $is_permitted_user->vehicle_types))){ ?>
                            <li>
                              <a href="{{route('admin.edit.service', array('id' => $service->id))}}">{{ tr('edit') }}</a>
                            </li>
                        <?php } ?>

                        <?php if($is_permitted_user->vehicle_types !='' && $is_permitted_user->vehicle_types !=0 && in_array(DELET, explode(',', $is_permitted_user->vehicle_types))){ ?>
                          <li>
                            <a onclick="return confirm('{{ tr('delete_confirmation') }}')" href="{{route('admin.delete.service', array('id' => $service->id))}}">{{ tr('delete') }}</a>
                          </li>
                      <?php } ?>
                        </ul>
                      </div>
                <?php } ?>
                </td>
            </tr>
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


@endsection
