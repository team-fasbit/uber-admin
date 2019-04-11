@extends('layouts.admin')

@if(isset($name))
  @section('title', tr('edit_promo_code'))
@else
  @section('title', tr('add_promo_code'))
@endif


@section('content-header', 'Promo Code Management')

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>Home</a></li>
    <li><a href="{{route('admin.service.types')}}"><i class="fa fa-user"></i>Promo Code Management</a></li>
    <li class="active">Add a Promo Code</li>
@endsection

@section('content')

@include('notification.notify')

<div class="row">

  <div class="col-md-12">

      <div class="box box-info">
          
          <div class="panel-heading border">

          </div>

          <form class="form-horizontal bordered-group" action="{{route('admin.promo_code.process')}}" method="POST" enctype="multipart/form-data" role="form">
            
            <!-- <div class="form-group">
              <label class="col-sm-3 control-label">{{ tr('scope') }}</label>
              <div class="col-sm-7">
                <input type="radio" name="scope" value="0" checked id="limited" onclick="scopeFun();" @if(isset($promo_code)) @if($promo_code->scope == 0) checked @endif @endif> Limited &nbsp;&nbsp;
                <input type="radio" name="scope" value="1" id="global" onclick="scopeFun();" @if(isset($promo_code)) @if($promo_code->scope == 1) checked @endif @endif> Global
              </div>
            </div> -->

            <div class="form-group coups">
              <label class="col-sm-3 control-label">{{ tr('coupon_code') }}</label>
              <div class="col-sm-7">
                <input type="text" name="coupon_code" value="{{ isset($promo_code->coupon_code) ? $promo_code->coupon_code : '' }}" class="form-control" placeholder="Eg: ABCDEF">
              </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">{{ tr('start_time') }}</label>
                <div class="col-sm-4">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  @php if(isset($promo_code)) { $start = explode(' ', $promo_code->start); } @endphp
                  <input type="text" class="form-control" name="start_date" value="{{ isset($promo_code) ? $start[0] : '' }}" id="start_datepicker" required>
                </div>
                </div>

                <div class="col-sm-3 bootstrap-timepicker">
                <div class="input-group date">
                  <div class="input-group-addon">
                  <i class="fa fa-clock-o"></i>
                  </div>
                  <input type="text" class="form-control timepicker" name="start_time" data-mode="12h" value="{{ isset($promo_code) ? $start_1 : '' }}" required>
                </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">{{ tr('end_time') }}</label>
                <div class="col-sm-4">
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  @php if(isset($promo_code)) { $end = explode(' ', $promo_code->end); } @endphp
                  <input type="text" class="form-control" name="end_date" value="{{ isset($promo_code) ? $end[0] : '' }}" id="end_datepicker" required>
                </div>
                </div>

                <div class="col-sm-3 bootstrap-timepicker">
                <div class="input-group date">
                  <div class="input-group-addon">
                  <i class="fa fa-clock-o"></i>
                  </div>
                  <input type="text" class="form-control timepicker" name="end_time" data-mode="12h"  value="{{ isset($promo_code) ? $end_1 : '' }}" required>
                </div>
                </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">{{ tr('short_desc') }}</label>
              <div class="col-sm-7">
                <input placeholder="A short description of the promotion" type="text" name="short_description" value="{{ isset($promo_code->short_description) ? $promo_code->short_description : '' }}" required class="form-control">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label">{{ tr('long_desc') }}</label>
              <div class="col-sm-7">
                <input placeholder="A detailed description of the promotion" type="text" name="long_description" value="{{ isset($promo_code->long_description) ? $promo_code->long_description : '' }}" required class="form-control">
              </div>
            </div>
            
            <div class="form-group">
              <label class="col-sm-3 control-label">{{ tr('type') }}</label>
              <div class="col-sm-7">
                <input type="radio" name="type" value="0" id="flat" checked onclick="typeFun();" @if(isset($promo_code)) @if($promo_code->type == 0) checked @endif @endif> Flat Off &nbsp;&nbsp;
                <input type="radio" name="type" value="1" id="percent" onclick="typeFun();" @if(isset($promo_code)) @if($promo_code->type == 1) checked @endif @endif> Percent Off
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-3 control-label labx">{{ tr('amount_tobe_discounted') }}</label>
              <div class="col-sm-7">
            <input type="text" class="form-control gomma" name="value" value="{{ isset($promo_code->value) ? $promo_code->value : '' }}" placeholder="$" required>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-3 control-label">{{ tr('max_promo') }}</label>
              <div class="col-sm-7">
                <input type="text" name="max_promo" value="{{ isset($promo_code->max_promo) ? $promo_code->max_promo : '' }}" required class="form-control" required>
              </div>

            </div>

          <div class="form-group">
              <label class="col-sm-3 control-label">{{ tr('max_usage') }}</label>
              <div class="col-sm-7">
                <input type="text" name="max_usage" value="{{ isset($promo_code->max_usage) ? $promo_code->max_usage : '' }}" required class="form-control" required>
              </div>
            </div>

            <input type="hidden" name="id" value="@if(isset($promo_code)) {{$promo_code->id}} @endif" />

            <div class="box-footer">
                <a href="{{route('admin.promo_codes')}}"><button type="button" class="btn btn-danger">{{tr('cancel')}}</button></a>
                <button type="submit" class="btn btn-success pull-right">{{tr('submit')}}</button>
            </div>

          </form>

      </div>

  </div>

</div>

<script type="text/javascript">
  function scopeFun() {
    if ($("#global").prop("checked")) {
      $(".coups").hide();
    } else {
      $(".coups").show();
    }
  }
  function typeFun() {
    if ($("#percent").prop("checked")) {
      $(".labx").html('Percent to be discounted');
      $(".gomma").attr('placeholder','%');
    } else {
      $(".labx").html('Amount to be discounted');
      $(".gomma").attr('placeholder','$');
    }
  }


</script>
@endsection
