<?php 
  use App\Admin; 
  $is_permitted_user = Admin::find(Auth::guard('admin')->user()->id);
?>
@extends('layouts.admin')

@section('title', tr('providers'))

@section('content-header', 'View all Drivers')

@section('breadcrumb')
	<li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
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
	                  <th class="min">Name</th>
	                  <th>{{ tr('email') }}</th>
	                  <th>{{ tr('total_request') }}</th>
	                  <th>{{ tr('accepted_requests') }}</th>
	                  <th>{{ tr('cancel_request') }}</th>
					  <th>Request Earnings</th>
					  <th>Admin Earnings</th>
					  <th>Provider Earnings</th>
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
					  <td>{{get_currency_value($provider->total_request_earnings ? $provider->total_request_earnings : 0)}}</td>
					  <td>{{get_currency_value( ($provider->total_request_earnings - $provider->total_provider_earnings) ? ($provider->total_request_earnings - $provider->total_provider_earnings) : 0)}}</td>
					  <td>{{get_currency_value($provider->total_provider_earnings ? $provider->total_provider_earnings : 0)}}</td>
	                  <td>@if($provider->is_available==1) <span class="label label-primary">{{ tr('yes')}}</span> @else <span class="label label-warning">N/A</span> @endif</td>
	                  <td>@if($provider->is_approved==1) <span class="label label-success">{{ tr('approved') }}</span> @else <span class="label label-danger">{{ tr('unapproved') }}</span> @endif</td>

	                  <td>
	                  	<?php if($is_permitted_user->providers !='' && $is_permitted_user->providers !=0){ ?>
											<ul class="admin-action btn btn-default">
													<li class="dropdown">
															<a class="dropdown-toggle" data-toggle="dropdown" href="#">
																{{tr('action')}} <span class="caret"></span>
															</a>
															<ul class="dropdown-menu">
							<?php if($is_permitted_user->providers !='' && $is_permitted_user->providers !=0 && in_array(EDIT, explode(',', $is_permitted_user->providers))){ ?>
                            <li>
                              <a href="{{route('admin.edit.provider', array('id' => $provider->id))}}">{{ tr('edit') }}</a>
                            </li>
                            <?php } ?>

                            <?php if($is_permitted_user->providers !='' && $is_permitted_user->providers !=0 && in_array(RESET_PASSWORD, explode(',', $is_permitted_user->providers))){ ?>
                            <li>
                              <a href="{{route('admin.password.getCredentials', array('email' => $provider->email,'reset_for' => 'provider'))}}">{{ tr('reset_password') }}</a>
                            </li>
                            <?php } ?>

                            <li>
                            @if($provider->is_approved==0)
                            <?php if($is_permitted_user->providers !='' && $is_permitted_user->providers !=0 && in_array(APPROVE, explode(',', $is_permitted_user->providers))){ ?>
                              <a href="{{route('admin.provider.approve', array('id' => $provider->id, 'status'=>1))}}">{{ tr('approve') }}</a>
                            <?php } ?>
                            @else
                            <?php if($is_permitted_user->providers !='' && $is_permitted_user->providers !=0 && in_array(DECLINE, explode(',', $is_permitted_user->providers))){ ?>
                              <a href="{{route('admin.provider.approve', array('id' => $provider->id, 'status' => 0))}}">{{ tr('decline') }}</a>
                            <?php } ?>
                            @endif
                            </li>

                            <?php if($is_permitted_user->providers !='' && $is_permitted_user->providers !=0 && in_array(DELET, explode(',', $is_permitted_user->providers))){ ?>
                            <li>
                              <a onclick="return confirm('{{ tr('delete_confirmation') }}')" href="{{route('admin.delete.provider', array('id' => $provider->id))}}">{{ tr('delete') }}</a>
                            </li>
                            <?php } ?>

                            <?php if($is_permitted_user->providers !='' && $is_permitted_user->providers !=0 && in_array(VIEW_HISTORY, explode(',', $is_permitted_user->providers))){ ?>
                            <li>
                              <a href="{{route('admin.provider.history', array('id' => $provider->id))}}">{{ tr('view_history') }}</a>
                            </li>
                            <?php } ?>

                            <?php if($is_permitted_user->providers !='' && $is_permitted_user->providers !=0 && in_array(VIEW_DOCUMENTS, explode(',', $is_permitted_user->providers))){ ?>
                            <li>
                              <a href="{{route('admin.provider.document', array('id' => $provider->id))}}">{{ tr('view_docs') }}</a>
                            </li>
                            <?php } ?>
                          </ul>
														</li>
												</ul>
						<?php } ?>
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
