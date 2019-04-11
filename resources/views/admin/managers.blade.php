<?php 
  use App\Admin; 
  $is_permitted_user = Admin::find(Auth::guard('admin')->user()->id);
?>
@extends('layouts.admin')

@section('title', tr('managers'))

@section('content-header', 'Managers registered under you')

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li class="active"><i class="fa fa-corporate"></i> {{tr('managers')}}</li>
@endsection

@section('content')

	@include('notification.notify')

	<div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-body">
            	<div class="map_content">
                    <p class="lead para_mid">
                       <b>  What is this?</b>
                    </p>
                     <p class="lead para_mid">
                   		 Present are the list of Businesses, Manager's registered under your company.
                     </p>
                     <p class="lead para_mid">
                     <b>Are they different from regular Drivers?</b>
                     </p>
                     <p class="lead para_mid">
                        Yes, you are right. You will have individual drivers driving under you. But corp orate's are separate business registered under your company. They will have their own fleet and work for you.
					</p>	
                 </div>
            	@if(count($corporates) > 0)

	              	<table id="example1" class="table table-bordered table-striped">

						<thead>
						    <tr>
						      <th>{{tr('id')}}</th>
						      <th>{{tr('name')}}</th>
						      <th>{{tr('email')}}</th>
						      <th>{{tr('mobile')}}</th>
						      <th>{{tr('address')}}</th>
						      <!-- <th>{{tr('status')}}</th> -->
						      <th>{{tr('action')}}</th>
						    </tr>
						</thead>

						<tbody>
							@foreach($corporates as $i => $corporate)

							    <tr>
							      	<td>{{$i+1}}</td>
							      	<td>{{$corporate->name}}</td>
							      	<td>{{$corporate->email}}</td>
                      <td>{{$corporate->mobile}}</td>
							      	<td>{{$corporate->address}}</td>
							      <!-- <td>
							      		if($corporate->is_activated)
							      			<span class="label label-success">{{tr('approved')}}</span>
							       		else
							       			<span class="label label-warning">{{tr('pending')}}</span>
							       		endif
							       </td> -->
							      <td>
							      <?php if($is_permitted_user->call_center_managers !='' && $is_permitted_user->call_center_managers !=0 ){ ?>
            							<ul class="admin-action btn btn-default">
            								<li class="dropdown">
								                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
								                  {{tr('action')}} <span class="caret"></span>
								                </a>
								                <ul class="dropdown-menu">

								       <?php  if($is_permitted_user->call_center_managers !='' && $is_permitted_user->call_center_managers !=0 && in_array(EDIT, explode(',', $is_permitted_user->call_center_managers))){ ?>
								                  	<li role="presentation"><a role="menuitem" tabindex="-1" href="{{route('admin.edit.manager' , array('id' => $corporate->id))}}">{{tr('edit_manager')}}</a></li>
								        <?php } ?>

							   <?php  if($is_permitted_user->call_center_managers !='' && $is_permitted_user->call_center_managers !=0 && in_array(RESET_PASSWORD, explode(',', $is_permitted_user->call_center_managers))){ ?>
								                  	<li>
                              <a href="{{route('admin.password.getCredentials', array('email' => $corporate->email,'reset_for' => 'manager'))}}">{{ tr('reset_password') }}</a>
                            </li>
                            <?php } ?>

                            <?php  if($is_permitted_user->call_center_managers !='' && $is_permitted_user->call_center_managers !=0 && in_array(VIEW_DETAILS, explode(',', $is_permitted_user->call_center_managers))){ ?>
                                    				<li role="presentation"><a role="menuitem" tabindex="-1" href="{{route('admin.view.manager' , array('option' => 'manager_details','id' => $corporate->id))}}">{{tr('view_manager')}}</a></li>
                                <?php } ?>

                                <?php  if($is_permitted_user->call_center_managers !='' && $is_permitted_user->call_center_managers !=0 && in_array(DELET, explode(',', $is_permitted_user->call_center_managers))){ ?>
								                  	<li role="presentation" class="divider"></li>
								                  	<li role="presentation">

								                  	 @if(Setting::get('admin_delete_control'))
								                  	 	<a role="button" href="javascript:;" class="btn disabled" style="text-align: left">{{tr('delete')}}</a>
								                  	 @else

								                  	 	<a role="menuitem" tabindex="-1"
								                  			onclick="return confirm('Are you sure?');" href="{{route('admin.delete.manager', array('id' => $corporate->id))}}">{{tr('delete')}}
								                  		</a>

								                  	 @endif

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
					<h3 class="no-result">{{tr('no_user_found')}}</h3>
				@endif
            </div>
          </div>
        </div>
    </div>

@endsection
