@extends('layouts.admin')

          @if(isset($name))
            @section('title', tr('edit_ads'))
          @else
            @section('title', tr('add_ads'))
          @endif

@if(isset($name))
  @section('content-header', tr('edit_ads'))
@else
  @section('content-header', tr('add_ads'))
@endif

@section('content')

@include('notification.notify')
        <div class="panel mb25 box box-info">
          <div class="panel-heading border">
          @if(isset($name))
          {{tr('edit_ads')}}
          @else
            
          @endif
          </div>
          <div class="panel-body">
            <div class="row no-margin">
              <div class="col-lg-12">
                <form class="form-horizontal bordered-group" action="{{route('admin.add_ads_process')}}" method="POST" enctype="multipart/form-data" role="form">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">{{ tr('picture') }}</label>
                    <div class="col-sm-8">
                    @if(isset($ads->picture))
                      <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{$ads->picture}}">
                    @endif
                      <input name="picture" type="file">
                      <p class="help-block">{{ tr('upload_message') }}</p>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10">
                      <input type="text" name="description" value="{{ isset($ads->description) ? $ads->description : '' }}" required class="form-control" placeholder="describe your add..">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label">Enter URL</label>
                    <div class="col-sm-10">
                      <input type="url" name="url" value="{{ isset($ads->url) ? $ads->url : '' }}" required class="form-control" placeholder="add url..">
                    </div>
                  </div>

                  <input type="hidden" name="id" value="@if(isset($ads)) {{$ads->id}} @endif" />

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
