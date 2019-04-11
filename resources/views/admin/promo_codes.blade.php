<?php 
  use App\Admin; 
  $is_permitted_user = Admin::find(Auth::guard('admin')->user()->id);
?>
@extends('layouts.admin')

@section('title', 'Promo Code')

@section('content-header', 'View all Promo Codes')

@section('breadcrumb')
  <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li class="active"><i class="fa fa-users"></i> Promo Code</li>
@endsection

@section('content')

  @include('notification.notify')

  <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-body">

              @if(count($promocodes) > 0)

                  <table id="example1" class="table table-bordered table-striped">

              <thead>
                  <tr>
                    <th>{{ tr('id') }}</th>
                    <th class="min">Name</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Start Date and Time</th>
                    <th>End Date and Time</th>
                    <th>{{ tr('action') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($promocodes as $index => $promo)
                <tr>
                    <td>{{$index + 1}}</td>
                    <td>@if($promo->coupon_code) {{$promo->coupon_code}}
                      @else 
                        @if($promo->type == 0) Flat ${{ $promo->value }} Off
                        @else {{ $promo->value }}% Off @endif
                      @endif
                     </td>
                    <td>{{$promo->short_description}}</td>
                    <td>@if($promo->scope == 0) {{ "COUPON" }} @else {{ "PROMOTION" }} @endif</td>
                    <td>{{$promo->start}}</td>
                    <td>{{$promo->end}}</td>

                    <td>
                      <?php if($is_permitted_user->promo_codes !='' && $is_permitted_user->promo_codes !=0){ ?>
                        <ul class="admin-action btn btn-default">
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                  {{tr('action')}} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                              <?php if($is_permitted_user->promo_codes !='' && $is_permitted_user->promo_codes !=0 && in_array(EDIT, explode(',', $is_permitted_user->promo_codes))){ ?>
                                <li>
                                  <a href="{{route('admin.edit.promo_code', array('id' => $promo->id))}}">{{ tr('edit') }}</a>
                                </li>
                              <?php } ?>
                              <?php if($is_permitted_user->promo_codes !='' && $is_permitted_user->promo_codes !=0 && in_array(DELET, explode(',', $is_permitted_user->promo_codes))){ ?>
                                <li>
                                  <a onclick="return confirm('{{ tr('delete_confirmation') }}')" href="{{route('admin.delete.promo_code', array('id' => $promo->id))}}">{{ tr('delete') }}</a>
                                </li>
                              <?php } ?>
                            </ul>
                              </li>
                          </ul>
                        <?php } ?>
                    </td>
                </tr>
                @endforeach
            </tbody>
          </table>
        @else
          <h3 class="no-result">No results found</h3>
        @endif
            </div>
          </div>
        </div>
    </div>

@endsection
