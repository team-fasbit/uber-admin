<?php 
  use App\Admin; 
  $is_permitted_user = Admin::find(Auth::guard('admin')->user()->id);
?>
@extends('layouts.admin')

@section('title', 'Currency')

@section('content-header', 'Currency Management')

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li class="active"><i class="fa fa-user"></i> Currency</li>
@endsection

@section('content')

  @include('notification.notify')


<div class="row">
      <div class="col-xs-12">
        <div class="box box-info">
          <div class="box-body">
            @if(count($currency) > 0)

          <table id="example1" class="table table-bordered table-striped">

            <thead>
              <tr>
                <th>{{ tr('id') }}</th>
                <th>{{ tr('currency_name') }}</th>
                <th>{{ tr('currency_value') }}</th>
                <th>{{ tr('action') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($currency as $index => $taken)
            <tr>
                <td>{{$index + 1}}</td>
                <td>{{$taken->currency_name}}</td>
                <td>{{$taken->currency_value}}</td>
                <td class="btn-left">
                  <?php if($is_permitted_user->currency_management !='' && $is_permitted_user->currency_management !=0){ ?>
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <?php if($is_permitted_user->currency_management !='' && $is_permitted_user->currency_management !=0 && in_array(EDIT, explode(',', 
                          $is_permitted_user->currency_management))){ ?>
                            <li>
                              <a href="{{route('admin.currency_edit', array('id' => $taken->id))}}">{{ tr('edit') }}</a>
                            </li>
                          <?php } ?>

                          <?php if($is_permitted_user->currency_management !='' && $is_permitted_user->currency_management !=0 && in_array(DELET, explode(',', $is_permitted_user->currency_management))){ ?>
                          <li>
                            <a onclick="return confirm('{{ tr('delete_confirmation') }}')" href="{{route('admin.document_delete', array('id' => $taken->id))}}">{{ tr('delete') }}</a>
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
