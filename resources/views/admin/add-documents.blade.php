@extends('layouts.admin')

          @if(isset($name))
            @section('title', 'Edit Document Type | ')
          @else
            @section('title', 'Add Document Type | ')
          @endif

@section('content')

@include('notification.notify')
        <div class="panel mb25 box box-info">
          <div class="panel-heading border">
          @if(isset($name))
          {{ tr('edit_document') }}
          @else
            
          @endif
          </div>
          <div class="panel-body">
               <div class="box-header">
                <div class="map_content">
                <p class="lead para_mid">
                   Use this screen to create a new Document Type. Each Document Type you create here will be showing up inside the Driver app & he would need to upload it via. the Mobile app.

                 </p>
                <p class="lead para_mid">
                   <b> What is this?</b>
                 </p>
                 <p class="lead para_mid">
                 While On-boarding a Driver to Drive for your business, you would need to verify the driver. Each Driver would need to submit certain Documents for your review ( Eg: License, RC, Vehicle Insurance, Code of Conduct certificate etc. ). 
                 </p>
                
              </div>
              </div>
            <div class="row no-margin">
              <div class="col-lg-12">
                <form class="form-horizontal bordered-group" action="{{route('admin.add_document_process')}}" method="POST" enctype="multipart/form-data" role="form">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">{{ tr('document_name') }}</label>
                    <div class="col-sm-10">
                      <input type="text" name="document_name" value="{{ isset($document->name) ? $document->name : '' }}" required class="form-control">
                    </div>
                  </div>
                  <input type="hidden" name="id" value="@if(isset($document)) {{$document->id}} @endif" />

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
