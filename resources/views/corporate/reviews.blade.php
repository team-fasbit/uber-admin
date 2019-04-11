@extends('layouts.corporate')

@if(isset($name))
  @section('title', tr('user_review'))
@else
  @section('title', tr('provider_review'))
@endif

@if(isset($name))
  @section('content-header', tr('user_review'))
@else
  @section('content-header', tr('provider_review'))
@endif


@section('breadcrumb')
    <li><a href="{{route('corporate.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    @if(isset($name))
      <li class="active"><i class="fa fa-user"></i> {{tr('user_review')}}</li>
    @else
      <li class="active"><i class="fa fa-user"></i> {{tr('provider_review')}}</li>
    @endif

@endsection

@section('content')

	@include('notification.notify')


  <div class="row">
        <div class="col-xs-12">
          <div class="box box-info">
            <div class="box-body">

            	@if(count($reviews) > 0)

          	<table id="example1" class="table table-bordered table-striped">

              <thead>
                  <tr>
                    @if(isset($name))
                    <th class="min">{{ tr('provider_name') }}</th>
                    <th class="min">{{ tr('user_name') }}</th>
                    @else
                    <th>{{ tr('user_name') }}</th>
                    <th>{{ tr('provider_name') }}</th>
                    @endif

                    <th>{{ tr('rating') }}</th>
                    <th class="min">{{ tr('date_time') }}</th>
                    <th class="min">{{ tr('comments') }}</th>
                    <th>{{ tr('action') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($reviews as $index => $review)
                <tr>
                    @if(isset($name))
                    <td>{{$review->provider_first_name . $review->provider_last_name}}</td>
                    <td>{{$review->user_first_name . $review->user_last_name}}</td>
                    @else
                    <td>{{$review->user_first_name . $review->user_last_name}}</td>
                    <td>{{$review->provider_first_name . $review->provider_last_name}}</td>
                    @endif

                    <td>{{$review->rating}}</td>
                    <td>{{$review->created_at}}</td>
                    <td>{{$review->comment}}</td>

                    <td>
                        <div class="input-group-btn">
                          @if(isset($name))
                          <button type="button" class="btn btn-danger"><a class="review_anchor" onclick="return confirm('{{ tr('delete_confirmation') }}')" href="{{route('corporate.user_review_delete', array('id' => $review->review_id))}}">{{ tr('delete') }}</a></button>
                          @else
                            <button type="button" class="btn btn-danger"><a class="review_anchor" onclick="return confirm('{{ tr('delete_confirmation') }}')" href="{{route('corporate.provider_review_delete', array('id' => $review->review_id))}}">{{ tr('delete') }}</a></button>
                          @endif
                        </div>
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
