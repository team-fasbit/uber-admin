<?php 
  use App\Admin; 
  $is_permitted_user = Admin::find(Auth::guard('admin')->user()->id);
?>
@extends('layouts.admin')

@section('title', tr('documents'))

@section('content-header', 'Documents Management')

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li class="active"><i class="fa fa-user"></i> {{tr('documents')}}</li>
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
                    Listed here are all the Document Types you have created. If you delete a document type, it will not appear in the Driver App.
                 </p>
                <p class="lead para_mid">
                    <b>What is this?</b>
                 </p>
                 <p class="lead para_mid">
                   While On-boarding a Driver to Drive for your business, you would need to verify the driver. Each Driver would need to submit certain Documents for your review ( Eg: License, RC, Vehicle Insurance, Code of Conduct certificate etc. ). 
                 </p>
                
              </div>
              </div>
            @if(count($documents) > 0)

          <table id="example1" class="table table-bordered table-striped">

            <thead>
              <tr>
                <th>{{ tr('id') }}</th>
                <th>{{ tr('doc_name') }}</th>
                <th>{{ tr('action') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($documents as $index => $document)
            <tr>
                <td>{{$index + 1}}</td>
                <td>{{$document->name}}</td>
                <td class="btn-left">
                  <?php if($is_permitted_user->documents_management !='' && $is_permitted_user->documents_management !=0){ ?>
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                          <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                          <?php if($is_permitted_user->documents_management !='' && $is_permitted_user->documents_management !=0 && in_array(EDIT, explode(',', $is_permitted_user->documents_management))){ ?>
                              <li>
                                <a href="{{route('admin.document_edit', array('id' => $document->id))}}">{{ tr('edit') }}</a>
                              </li>
                          <?php } ?>

                        <?php if($is_permitted_user->documents_management !='' && $is_permitted_user->documents_management !=0 && in_array(DELET, explode(',', $is_permitted_user->documents_management))){ ?>
                          <li>
                            <a onclick="return confirm('{{ tr('delete_confirmation') }}')" href="{{route('admin.document_delete', array('id' => $document->id))}}">{{ tr('delete') }}</a>
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
