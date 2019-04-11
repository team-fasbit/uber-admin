@extends('layouts.admin')

          @if(isset($name))
            @section('title', 'Edit Currency Type')
          @else
            @section('title', 'Add Currency Type')
          @endif

@if(isset($name))
  @section('content-header', 'Edit Currency')
@else
  @section('content-header', 'Add Currency')
@endif

@section('content')

@include('notification.notify')
        <div class="panel mb25 box box-info">
          <div class="panel-heading border">
          @if(isset($name))
          {{ tr('edit_currency') }}
          @else
            
          @endif
          </div>
          <div class="panel-body">
            <div class="row no-margin">
              <div class="col-lg-12">
                <form class="form-horizontal bordered-group" action="{{route('admin.add_currency_process')}}" method="POST" enctype="multipart/form-data" role="form">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Currency Name</label>
                    <div class="col-sm-10">
                      <input type="text" name="currency_name" value="{{ isset($currency->currency_name) ? $currency->currency_name : '' }}" required class="form-control" placeholder="USD">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Currency Value</label>
                    <div class="col-sm-10">
                      <input type="text" name="currency_value" value="{{ isset($currency->currency_value) ? $currency->currency_value : '' }}" required class="form-control" placeholder="12.5">
                    </div>
                  </div>
                  <input type="hidden" name="id" value="@if(isset($currency)) {{$currency->id}} @endif" />

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
