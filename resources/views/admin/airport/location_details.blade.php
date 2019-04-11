<?php 
  use App\Admin; 
  $is_permitted_user = Admin::find(Auth::guard('admin')->user()->id);
?>
@extends('layouts.admin')

@section('title', tr('location_details'))

@section('content-header', tr('location_details'))

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li class="active"><i class="fa fa-user"></i> {{tr('location_details')}}</li>
@endsection

@section('content')

  @include('notification.notify')


<div class="row">
      <div class="col-xs-12">
        <div class="box box-info">
          <div class="box-body">
            <div class="box-header">
                <div class="map_content">
                <p class="lead para_mid">
                Use this screen to manage all destination's.
                 </p>
                 <p class="lead para_mid">
                Note: Deleting a destination here, will also remove it from the Passenger app.
                </p>
              
              </div>
              </div>
            @if(count($location_details) > 0)

          <table id="example1" class="table table-bordered table-striped">

            <thead>
              <tr>
                <th>{{ tr('id') }}</th>
                <th class="min">Destination</th>
                <th class="min">Zip-code</th>
                <th>{{ tr('action') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($location_details as $index => $location_detail)
            <tr>
                <td>{{$index + 1 }}</td>
                <td>{{$location_detail->name}}</td>
                <td>{{$location_detail->zipcode}}</td>
                <td class="btn-left">
                  <?php if($is_permitted_user->destination_details !='' && $is_permitted_user->destination_details !=0){ ?>
                      <div class="input-group-btn">
                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">{{ tr('action') }}
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu">
                            <?php if($is_permitted_user->destination_details !='' && $is_permitted_user->destination_details !=0 && in_array(EDIT, explode(',', $is_permitted_user->destination_details))){ ?>
                                <li>
                                  <a href="{{route('admin.edit.location_detail', array('id' => $location_detail->id))}}">{{ tr('edit') }}</a>
                                </li>
                            <?php } ?>

                            <?php if($is_permitted_user->destination_details !='' && $is_permitted_user->destination_details !=0 && in_array(DELET, explode(',', $is_permitted_user->destination_details))){ ?>
                              <li>
                                <a onclick="return confirm('{{ tr('delete_confirmation') }}')" href="{{route('admin.delete.location_detail', array('id' => $location_detail->id))}}">{{ tr('delete') }}</a>
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
