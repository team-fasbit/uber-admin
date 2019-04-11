@extends('layouts.corporate')

@section('title', tr('providers'))

@section('content-header', tr('providers'))

@section('breadcrumb')
	<li><a href="{{route('corporate.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li class="active"><i class="fa fa-users"></i> {{tr('providers')}}</li>
@endsection

@section('content')

	@include('notification.notify')

	<div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-body">

            	@if(count($providers) > 0)

	              	<table id="example1" class="table table-bordered table-striped">

							<thead>
	                <tr>
	                  <th>{{ tr('id') }}</th>
	                  <th class="min">{{ tr('full_name') }}</th>
	                  <th>{{ tr('email') }}</th>
	                  <th>{{ tr('total_request') }}</th>
	                  <th>{{ tr('accepted_requests') }}</th>
	                  <th>{{ tr('cancel_request') }}</th>
	                  <th>{{ tr('availability') }}</th>
	                  <th>{{ tr('status') }}</th>
	                  <th>{{ tr('action') }}</th>
	                  </tr>
	              </thead>
	              <tbody>
	              @foreach($providers as $index => $provider)
	              <tr>
	                  <td>{{$index + 1}}</td>
	                  <td>{{$provider->first_name}} {{$provider->last_name}}</td>
	                  <td>{{$provider->email}}</td>
	                  <td>{{$provider->total_requests}}</td>
	                  <td>{{$provider->accepted_requests}}</td>
	                  <td>{{$provider->total_requests -$provider->accepted_requests }}</td>
	                  <td>@if($provider->is_available==1) <span class="label label-primary">{{ tr('yes')}}</span> @else <span class="label label-warning">N/A</span> @endif</td>
	                  <td>@if($provider->is_approved==1) <span class="label label-success">{{ tr('approved') }}</span> @else <span class="label label-danger">{{ tr('unapproved') }}</span> @endif</td>

	                  <td>
											<ul class="admin-action btn btn-default">
													<li class="dropdown">
															<a class="dropdown-toggle" data-toggle="dropdown" href="#">
																{{tr('action')}} <span class="caret"></span>
															</a>
															<ul class="dropdown-menu">
                            <li>
                              <a href="{{route('corporate.edit.provider', array('id' => $provider->id))}}">{{ tr('edit') }}</a>
                            </li>
                            <li>
                            @if($provider->is_approved==0)
                              <a href="{{route('corporate.provider.approve', array('id' => $provider->id, 'status'=>1))}}">{{ tr('approve') }}</a>
                            @else
                              <a href="{{route('corporate.provider.approve', array('id' => $provider->id, 'status' => 0))}}">{{ tr('decline') }}</a>
                            @endif
                            </li>
                            <li>
                              <a onclick="return confirm('{{ tr('delete_confirmation') }}')" href="{{route('corporate.delete.provider', array('id' => $provider->id))}}">{{ tr('delete') }}</a>
                            </li>
                            <li>
                              <a href="{{route('corporate.provider.history', array('id' => $provider->id))}}">{{ tr('view_history') }}</a>
                            </li>
                            <li>
                              <a href="{{route('corporate.provider.document', array('id' => $provider->id))}}">{{ tr('view_docs') }}</a>
                            </li>

                          </ul>
														</li>
												</ul>
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
