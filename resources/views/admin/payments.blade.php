@extends('layouts.admin')

@section('title', tr('payment_details'))

@section('content-header', tr('payment_details'))

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li class="active"><i class="fa fa-user"></i> {{tr('payment_details')}}</li>
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
                 Listed here are all the Transactions & Payments made by each passenger for the rides undertaken by them.
                 </p>
               
              </div>
            @if(count($payments) > 0)

                <table id="example1" class="table table-bordered table-striped">

                  <thead>
                    <tr>
                      <th>{{ tr('request_id') }}</th>
                      <th>{{ tr('transaction_id') }}</th>
                      <th>Paid by</th>
                      <th>Paid to</th>
                      <th>{{ tr('total_amount') }}</th>
                      <th>{{ tr('payment_mode') }}</th>
                      <th>{{ tr('date_time') }}</th>
                      <th>{{ tr('payment_status') }}</th>
                      </tr>
                  </thead>
                  <tbody>
                  @foreach($payments as $index => $payment)
                  <tr>
                      <td>{{$payment->request_id}}</td>
                      <td>{{$payment->payment_id}}</td>
                      <td>{{$payment->user_first_name.' '.$payment->user_last_name}}</td>
                      <td>{{$payment->provider_first_name.' '.$payment->provider_last_name}}</td>
                      <td>{{get_currency_value($payment->total)}}</td>
                      <td>{{$payment->payment_mode}}</td>
                      <td>{{$payment->created_at}}</td>
                      <td>@if($payment->status==0) <span class="label label-danger">{{ tr('not_paid') }}</span> @else <span class="label label-success">{{ tr('paid') }}</span> @endif</td>

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
