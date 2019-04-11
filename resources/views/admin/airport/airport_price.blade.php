<?php 
  use App\Admin; 
  $is_permitted_user = Admin::find(Auth::guard('admin')->user()->id);
?>
@extends('layouts.admin')

@section('title', tr('airport_pricings'))

@section('content-header', tr('airport_pricings'))

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li class="active"><i class="fa fa-user"></i> {{tr('airport_pricings')}}</li>
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
                Use this screen to manage all the Airport Pricing plans, you have setup earlier.
                 </p>
                 <p class="lead para_mid">
                 Note: Deleting a Pricing plan, will remove it from the Passenger app as well.

                </p>
              
              </div>
            @if(count($airport_pricings) > 0)

          <table id="example1" class="table table-bordered table-striped">

            <thead>
              <tr>
                <th>{{ tr('id') }}</th>
                <th class="min">{{ tr('airport_name') }}</th>
                <th class="min">{{ tr('location_name') }}</th>
                <th class="min">{{ tr('number_tolls') }}</th>
                <th class="min">{{ tr('price') }}</th>
                <th class="min">{{ tr('service_type') }}</th>
                <th>{{ tr('action') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($airport_pricings as $index => $airport_price)
            <tr>
                <td>{{$index + 1 }}</td>
                <td>{{$airport_price->airport_details->name}}</td>
                <td>{{$airport_price->location_details->name}}</td>
                <td>{{$airport_price->number_tolls}}</td>
                <td>{{$airport_price->price}}</td>
                <td>{{$airport_price->service_type->name}}</td>
                
                
                <td class="btn-left">
                  <?php if($is_permitted_user->pricing_management !='' && $is_permitted_user->pricing_management !=0 && in_array(ADD, explode(',', $is_permitted_user->pricing_management))){ ?>
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">{{ tr('action') }}
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <?php if($is_permitted_user->pricing_management !='' && $is_permitted_user->pricing_management !=0 && in_array(EDIT, explode(',', $is_permitted_user->pricing_management))){ ?>
                            <li>
                              <a href="{{route('admin.edit.airport_pricing', array('id' => $airport_price->id))}}">{{ tr('edit') }}</a>
                            </li>
                          <?php } ?>

                          <?php if($is_permitted_user->pricing_management !='' && $is_permitted_user->pricing_management !=0 && in_array(DELET, explode(',', $is_permitted_user->pricing_management))){ ?>
                          <li>
                            <a onclick="return confirm('{{ tr('delete_confirmation') }}')" href="{{route('admin.delete.airport_pricing', array('id' => $airport_price->id))}}">{{ tr('delete') }}</a>
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
