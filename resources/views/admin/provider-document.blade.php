@extends('layouts.admin')

@section('title', tr('view_documents'))

@section('content-header', tr('view_documents'))

@section('breadcrumb')
<li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
<li><a href="{{route('admin.providers')}}"><i class="fa fa-user"></i> {{tr('providers')}}</a></li>
<li class="active"><i class="fa fa-user"></i> {{tr('view_documents')}}</li>
@endsection

@section('content')

	@include('notification.notify')

	<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">

            	@if(count($documents) > 0)

	              	<table id="example1" class="table table-bordered table-striped">

                    <thead>
                      <tr>
                      <th>Provider ID</th>
                      <th>Provider Name</th>
                      <th>Document Type</th>
                      <th>View</th>
                  </tr>
                    </thead>
                    <tbody>
                    @foreach($documents as $index => $doc)
                     <tr>
                          <td>{{$provider->id}}</td>
                          <td>{{$provider->first_name." ".$provider->last_name}}</td>
                          <td>{{$doc->document_name}}</td>
                          <td><a href="{{ $doc->document_url }}" target="_blank"><span class="btn btn-info btn-large">View</span></a></td>
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
