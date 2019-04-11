@extends('layouts.corporate')

@if(isset($name))
  @section('title', tr('view_history'))
@else
  @section('title', tr('requests'))
@endif

@if(isset($name))
  @section('content-header', tr('view_history'))
@else
  @section('content-header', tr('requests'))
@endif


@section('breadcrumb')
    <li><a href="{{route('corporate.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    @if(isset($name))
      <li><a href="{{route('corporate.users')}}"><i class="fa fa-user"></i> {{tr('users')}}</a></li>
      <li class="active"><i class="fa fa-university"></i> {{tr('view_history')}}</li>
    @else

      <li class="active"><i class="fa fa-university"></i> {{tr('requests')}}</li>
    @endif

@endsection

@section('content')

    @include('notification.notify')

<div class="row">
  <div class="col-xs-12">
    <div class="box box-info">

      <div class="box-body">

      	@if(count($requests) > 0)

          	<table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th class="min">User Name</th>
                  <th class="min">Provider Name</th>
                  <th class="min">DateTime</th>
                  <th>Status</th>
                  <th>Amount</th>
                  <th>Payment Mode</th>
                  <th>Payment Status</th>
                  <th>Action</th>
                  </tr>
              </thead>
              <tbody>
              @foreach($requests as $index => $requestss)
              <tr>
                  <td>{{$index + 1}}</td>
                  <td>{{$requestss->user_first_name . " " . $requestss->user_last_name}}</td>
                  <td>@if($requestss->confirmed_provider){{$requestss->provider_first_name . " " . $requestss->provider_last_name}} @else - @endif</td>
                  <td>{{$requestss->date}}</td>
                  <td>@if($requestss->status == 0)
                            New
                      @elseif($requestss->status == 1)
                            Waiting
                      @elseif($requestss->status == 2)

                        @if($requestss->provider_status == 0)
                            Provider Not Found
                        @elseif($requestss->provider_status == 1)
                            Provider Accepted
                        @elseif($requestss->provider_status == 2)
                            Provider Started
                        @elseif($requestss->provider_status == 3)
                            Provider Arrived
                        @elseif($requestss->provider_status == 4)
                            Service Started
                        @elseif($requestss->provider_status == 5)
                            Service Completed
                        @elseif($requestss->provider_status == 6)
                            Provider Rated
                        @endif

                        @elseif($requestss->status == 3)

                              Payment Pending
                        @elseif($requestss->status == 4)

                              Request Rating
                        @elseif($requestss->status == 5)

                              Request Completed
                        @elseif($requestss->status == 6)

                              Request Cancelled
                        @elseif($requestss->status == 7)

                              Provider Not Available
                        @endif
                    </td>
                    <td>{{get_currency_value($requestss->amount ? $requestss->amount : 0)}}</td>
                    <td>{{$requestss->payment_mode}}</td>
                    <td>@if($requestss->payment_status==0) <span class="label label-danger">Not Paid</span> @else <span class="label label-success">Paid</span> @endif</td>
                  <td>
                      <div class="input-group-btn">
                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                            <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu">

                            <li>
                              <a href="{{route('corporate.view.request', array('id' => $requestss->id))}}">View Request</a>
                            </li>

                          </ul>
                        </div>
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
