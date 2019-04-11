<?php 
  use App\Admin; 
?>
@extends('layouts.admin')

@section('title', tr('corporates'))

@section('content-header', 'Corporates registered under you')

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li class="active"><i class="fa fa-corporate"></i> {{tr('corporates')}}</li>
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
                   		 Present are the list of Businesses, Corporate's registered under your company.
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
            							<ul class="admin-action btn btn-default">
            					<?php $is_permitted_user = Admin::find(Auth::guard('admin')->user()->id);
            					if($is_permitted_user->corporates !='' && $is_permitted_user->corporates !=0){ ?>
            								<li class="dropdown">
								                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
								                  {{tr('action')}} <span class="caret"></span>
								                </a>
								                <ul class="dropdown-menu">
								              <?php if($is_permitted_user->corporates !='' && $is_permitted_user->corporates !=0 && in_array(EDIT, explode(',', $is_permitted_user->corporates))){ ?>
								                  	<li role="presentation"><a role="menuitem" tabindex="-1" href="{{route('admin.edit.corporate' , array('id' => $corporate->id))}}">{{tr('edit_corporate')}}</a></li>
								            <?php } ?>

								            <?php if($is_permitted_user->corporates !='' && $is_permitted_user->corporates !=0 && in_array(RESET_PASSWORD, explode(',', $is_permitted_user->corporates))){ ?>
								                  	<li>
						                              <a href="{{route('admin.password.getCredentials', array('email' => $corporate->email,'reset_for' => 'corporate'))}}">{{ tr('reset_password') }}</a>
						                            </li>
						                    <?php } ?>

						                    <?php if($is_permitted_user->corporates !='' && $is_permitted_user->corporates !=0 && in_array(VIEW_DETAILS, explode(',', $is_permitted_user->corporates))){ ?>
                                    				<li role="presentation"><a role="menuitem" tabindex="-1" href="{{route('admin.view.corporate' , array('option' => 'corporate_details','id' => $corporate->id))}}">{{tr('view_corporate')}}</a></li>
                                    			<?php } ?>

                                    			<?php if($is_permitted_user->corporates !='' && $is_permitted_user->corporates !=0 && in_array(DELET, explode(',', $is_permitted_user->corporates))){ ?>
								                  	<li role="presentation" class="divider"></li>
								                  	<li role="presentation">

								                  	 @if(Setting::get('admin_delete_control'))
								                  	 	<a role="button" href="javascript:;" class="btn disabled" style="text-align: left">{{tr('delete')}}</a>
								                  	 @else

								                  	 	<a role="menuitem" tabindex="-1"
								                  			onclick="return confirm('Are you sure?');" href="{{route('admin.delete.corporate', array('id' => $corporate->id))}}">{{tr('delete')}}
								                  		</a>

								                  	 @endif

								                  	</li>
								                <?php } ?>

								                </ul>
              								</li>
              						<?php } ?>
            							</ul>
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
