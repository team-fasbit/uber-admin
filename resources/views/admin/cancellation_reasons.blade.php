<?php 
  use App\Admin; 
  $is_permitted_user = Admin::find(Auth::guard('admin')->user()->id);
?>
@extends('layouts.admin')

@section('title', 'Cancellation Reasons')

@section('content-header', 'Cancellation Reasons')

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li class="active"><i class="fa fa-user"></i>Reasons</li>
@endsection

@section('content')

@include('notification.notify')

<div class="row">
      <div class="col-xs-12">
        <div class="box box-info">
          <div class="box-body">
            @if(count($result) > 0)

          <table id="example1" class="table table-bordered table-striped">
            <thead>

              <tr>
                <th>{{ tr('id') }}</th>
                <th>Cancel Reason</th>
                <th>Cancel Fee (Absolute)</th>
                <th>{{ tr('action') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($result as $index => $taken)
            <tr>
                <td>{{$index + 1}}</td>
                <td>{{$taken->cancel_reason}}</td>
                <td>{{$taken->cancel_fee}}</td>
                <td class="btn-left">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                              <a href="{{route('admin.cancellation_reason_edit', array('id' => $taken->id))}}">{{ tr('edit') }}</a>
                            </li>

                          <li>
                            <a onclick="return confirm('{{ tr('delete_confirmation') }}')" href="{{route('admin.cancellation_reason_delete', array('id' => $taken->id))}}">{{ tr('delete') }}</a>
                          </li>
                        </ul>
                      </div>
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
