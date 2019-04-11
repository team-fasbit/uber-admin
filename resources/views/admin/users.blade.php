<?php 
    use App\Admin; 
    $is_permitted_user = Admin::find(Auth::guard('admin')->user()->id);
    ?>
@extends('layouts.admin')
@section('title', tr('users'))
@section('content-header', 'View all Passengers')
@section('breadcrumb')
<li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
<li class="active"><i class="fa fa-user"></i> View all Passengers</li>
@endsection
@section('content')
@include('notification.notify')
<div class="row">
    <div class="col-xs-12">
        <div class="box box-info">
            <div class="box-body">
                <div class="map_content">
                    <p class="lead para_mid">
                        Here you will see the list of all Passengers who have used your service so far. The list includes passengers who registered via. the Mobile apps & passengers to whom Taxi's have been manually dispatched as well.
                    </p>
                </div>
                @if(count($users) > 0)
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{tr('id')}}</th>
                            <th>Name</th>
                            <th>{{tr('email')}}</th>
                            <th>{{tr('mobile')}}</th>
                            <!-- <th>Location</th> -->
                            <!-- <th>{{tr('status')}}</th> -->
                            <th>Tron Address</th>
                            <th>Tron Balance</th>
                            <th>{{tr('action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $i => $user)
                        <tr class="user_rows" data-user-id="{{$user->id}}" id="row_{{$user->id}}">
                            <td>{{$i+1}}</td>
                            <td>{{$user->first_name}} {{$user->last_name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->mobile}}</td>
                            <td class="tron_address">Loading...</td>
                            <td class="tron_balance">Loading...</td>
                            {{--
                            <td>{{$user->address}}</td>
                            --}}
                            <td>
                                <?php if($is_permitted_user->users !='' && $is_permitted_user->users !=0 ){ ?>
                                <ul class="admin-action btn btn-default">
                                    <li class="dropdown">
                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                        {{tr('action')}} <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <?php if($is_permitted_user->users !='' && $is_permitted_user->users !=0 && in_array(EDIT, explode(',', $is_permitted_user->users))){ ?>
                                            <li role="presentation"><a role="menuitem" tabindex="-1" href="{{route('admin.edit.user' , array('id' => $user->id))}}">{{tr('edit_user')}}</a></li>
                                            <?php } ?>
                                            <?php if($is_permitted_user->users !='' && $is_permitted_user->users !=0 && in_array(RESET_PASSWORD, explode(',', $is_permitted_user->users))){ 
                                                ?>	            
                                            <li>
                                                <a href="{{route('admin.password.getCredentials', array('email' => $user->email,'reset_for' => 'user'))}}">{{ tr('reset_password') }}</a>
                                            </li>
                                            <?php } ?>
                                            <?php if($is_permitted_user->users !='' && $is_permitted_user->users !=0 && in_array(VIEW_DETAILS, explode(',', $is_permitted_user->users))){ 
                                                ?>
                                            <li role="presentation"><a role="menuitem" tabindex="-1" href="{{route('admin.view.user' , array('option' => 'user_details','id' => $user->id))}}">{{tr('view_user')}}</a></li>
                                            <?php } ?>
                                            <?php if($is_permitted_user->users !='' && $is_permitted_user->users !=0 && in_array(VIEW_HISTORY, explode(',', $is_permitted_user->users))){ 
                                                ?>
                                            <li role="presentation"><a role="menuitem" tabindex="-1" href="{{route('admin.user.history' , array('option' => 'user_history', 'id' => $user->id))}}">{{tr('view_history')}}</a></li>
                                            <?php } ?>
                                            <?php if($is_permitted_user->users !='' && $is_permitted_user->users !=0 && in_array(DELET, explode(',', $is_permitted_user->users))){ 
                                                ?>
                                            <li role="presentation" class="divider"></li>
                                            <li role="presentation">
                                                @if(Setting::get('admin_delete_control'))
                                                <a role="button" href="javascript:;" class="btn disabled" style="text-align: left">{{tr('delete_user')}}</a>
                                                @else
                                                <a role="menuitem" tabindex="-1"
                                                    onclick="return confirm('Are you sure?');" href="{{route('admin.delete.user', array('id' => $user->id))}}">{{tr('delete_user')}}
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
@section('bottom-scripts')
<script>
	var balancesApi = '{{route('admin.users.tron.balances')}}'
	var csrf_token = "{{csrf_token()}}"
	$(document).ready(function(){

		$(".user_rows").each(function(index, elem){
			console.log(index, elem);

			var uids = $(elem).data('user-id')
			$.post(balancesApi, {_token:csrf_token, user_ids : uids}, function(response){
				console.log(response)
				var data = response[0]

				$(elem).find('.tron_balance').text(data.balance + " TRX")
				$(elem).find('.tron_address').text(data.address.address_base58 + " TRX")

			})


		})


	})
</script>
@endsection