<?php 
  use App\Admin; 
  $is_permitted_user = Admin::find(Auth::guard('admin')->user()->id);
?>
@extends('layouts.admin')

@section('title', tr('airport_details'))

@section('content-header', tr('airport_details'))

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li class="active"><i class="fa fa-user"></i> {{tr('airport_details')}}</li>
@endsection

@section('content')

  @include('notification.notify')


<div class="row">
      <div class="col-xs-12">
        <div class="box box-info">
              <div class="box-header">
                <div class="map_content">
                <p class="lead para_mid">
                  Use this screen to manage the Airports you have added earlier.
                 </p>
                 <p class="lead para_mid">
                   Note: Deleting an Airport, will remove it from the Passenger app as well.
                </p>
              </div>
              </div>
          <div class="box-body">
    
            @if(count($airport_details) > 0)

          <table id="example1" class="table table-bordered table-striped">

            <thead>
              <tr>
                <th>{{ tr('id') }}</th>
                <th class="min">Airport Name</th>
                <th class="min">Zip-code</th>
                <th>{{ tr('action') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($airport_details as $index => $airport_detail)
            <tr>
                <td>{{$index + 1 }}</td>
                <td>{{$airport_detail->name}}</td>
                <td>{{$airport_detail->zipcode}}</td>
                <td class="btn-left">
                  <?php if($is_permitted_user->airport_details !='' && $is_permitted_user->airport_details !=0){ 
                            ?>
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">{{ tr('action') }}
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <?php if($is_permitted_user->airport_details !='' && $is_permitted_user->airport_details !=0 && in_array(EDIT, explode(',', $is_permitted_user->airport_details))){ 
                            ?>
                            <li>
                              <a href="{{route('admin.edit.airport_detail', array('id' => $airport_detail->id))}}">{{ tr('edit') }}</a>
                            </li>
                          <?php } ?>

                          <?php if($is_permitted_user->airport_details !='' && $is_permitted_user->airport_details !=0 && in_array(DELET, explode(',', $is_permitted_user->airport_details))){ 
                            ?>
                            <li>
                              <a onclick="return confirm('{{ tr('delete_confirmation') }}')" href="{{route('admin.delete.airport_detail', array('id' => $airport_detail->id))}}">{{ tr('delete') }}</a>
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
