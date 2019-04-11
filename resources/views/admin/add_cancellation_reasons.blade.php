@extends('layouts.admin')

@if(isset($name))
  @section('title', 'Edit Cancellation Reason')
@else
  @section('title', 'Add Cancellation Reason')
@endif

@if(isset($name))
  @section('content-header', 'Edit Cancellation Reason')
@else
  @section('content-header', 'Add Cancellation Reason')
@endif

@section('content')

@include('notification.notify')
        <div class="panel mb25 box box-info">
          <div class="panel-body">
            <div class="row no-margin">
              <div class="col-lg-12">
                <form class="form-horizontal bordered-group" action="{{route('admin.add_cancellation_reason_process')}}" method="POST" enctype="multipart/form-data" role="form">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Cancellation Reason</label>
                    <div class="col-sm-10">
                      <input type="text" name="cancel_reason" value="{{ isset($result->cancel_reason) ? $result->cancel_reason : '' }}" required class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Cancel Fee</label>
                    <div class="col-sm-10">
                      <input type="text" name="cancel_fee" value="{{ isset($result->cancel_fee) ? $result->cancel_fee : '' }}" required class="form-control">
                    </div>
                  </div>
                  <input type="hidden" name="id" value="@if(isset($result)) {{$result->id}} @endif" />

                <div class="form-group">
                  <label></label>
                  <div class="pull-right">
                    <button class="btn btn-success mr10">{{ tr('submit') }}</button>
                  </div>
                </div>

                </form>
              </div>
            </div>
          </div>
        </div>
@endsection