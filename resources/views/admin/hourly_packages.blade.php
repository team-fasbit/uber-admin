<?php 
  use App\Admin; 
  $is_permitted_user = Admin::find(Auth::guard('admin')->user()->id);
?>
@extends('layouts.admin')

@section('title', tr('hourly_packages'))

@section('content-header', 'Rentals Management')

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li class="active"><i class="fa fa-user"></i> Rentals Management</li>
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
             Here you can manage all the Hourly packages created earlier.
            </p>
             <p class="lead para_mid">
             Note: Deleting a package will remove it from the Passenger app display as well.
            </p>
          </div>
          </div>
            @if(count($hourly_packages) > 0)

          <table id="example1" class="table table-bordered table-striped">

            <thead>
              <tr>
                <th>{{ tr('id') }}</th>
                <th class="min">{{ tr('number_hours') }}</th>
                <th class="min">{{ tr('price') }}</th>
                <th class="min">For how many miles?</th>
                <th class="min">Vehicle type</th>
                <th>{{ tr('action') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($hourly_packages as $index => $hourly_package)
            <tr>
                <td>{{$index + 1 }}</td>
                <td>{{$hourly_package->number_hours}}</td>
                <td>{{$hourly_package->price}}</td>
                <td>{{$hourly_package->distance}}</td>
                <td>{{$hourly_package->name}}</td>
                <td class="btn-left">
                  <?php if($is_permitted_user->rental_management !='' && $is_permitted_user->rental_management !=0){ ?> 
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">{{ tr('action') }}
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <?php if($is_permitted_user->rental_management !='' && $is_permitted_user->rental_management !=0 && in_array(EDIT, explode(',', $is_permitted_user->rental_management))){ ?> 
                            <li>
                              <a href="{{route('admin.edit.hourly_package', array('id' => $hourly_package->id))}}">{{ tr('edit') }}</a>
                            </li>
                            <?php } ?>

                            <?php if($is_permitted_user->rental_management !='' && $is_permitted_user->rental_management !=0 && in_array(DELET, explode(',', $is_permitted_user->rental_management))){ ?> 
                            <li>
                              <a onclick="return confirm('{{ tr('delete_confirmation') }}')" href="{{route('admin.delete.hourly_package', array('id' => $hourly_package->id))}}">{{ tr('delete') }}</a>
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
