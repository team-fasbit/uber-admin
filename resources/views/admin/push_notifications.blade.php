@extends('layouts.admin')

@if(isset($name))
  @section('title', 'Push Notifications')
@else
  @section('title', 'Push Notifications')
@endif

@if(isset($name))
  @section('content-header', 'Push Notifications')
@else
  @section('content-header', 'Send Push Notifications')
@endif


@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>{{tr('home')}}</a></li>
    <li><a href="{{route('admin.corporates')}}"><i class="fa fa-users"></i> 'Push Notifications'</a></li>
    @if(isset($name))
      <li class="active">{{tr('edit_corporate')}}</li>
    @else
      <li class="active">{{tr('add_corporate')}}</li>
    @endif

@endsection

@section('content')

@include('notification.notify')

    <div class="row">

        <div class="col-md-12">

            <div class="box box-info">
            <br/>
              <form class="form-horizontal bordered-group" action="{{route('admin.mass_push_notification_send')}}" method="POST" role="form">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Push Title</label>
                      <div class="col-sm-8">
                        <input type="text" name="push_title" value="{{ old('push_title') }}" required class="form-control">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">Select Users</label>
                      <span><input type="checkbox" id="onCheckAll" onclick="funMania();">All</span>
                      <strong id="userCount" style="display:none;color:green;margin-left:5px;">  You have selected <span id="userSize"></span> users.</strong>
                      <div class="col-sm-8">
                        <select class="form-control" required id="allUsers" name="numbers[]" multiple="multiple" style="height:200px;">
                            @foreach($result as $taken)
                              @if($taken->device_token!='' && $taken->device_type=='android')
                            <option value="{{ $taken->id }}">{{ $taken->first_name }} {{ $taken->last_name }} ({{ $taken->email }})</option>
                              @endif
                            @endforeach
                            </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label">Enter the Message</label>
                      <div class="col-sm-8">
                        <textarea name="push_message" required class="form-control" rows="3"></textarea>
                      </div>
                    </div>

                    <div class="box-footer">
                        <input type="hidden" name="type" value="users">
                        <button type="reset" class="btn btn-danger">{{tr('cancel')}}</button>
                        <button type="submit" class="btn btn-success pull-right">{{tr('submit')}}</button>
                    </div>

                  </form>
            </div>

        </div>

    </div>
<script src="{{asset('admin-css/plugins/jQuery/jQuery-2.2.0.min.js')}}"></script>
<script type="text/javascript">
  $('#onCheckAll').change(function() {
        // alert('test');
        if($(this).is(":checked")) {
      $('#allUsers option').prop('selected', true);
      var size = $('#allUsers option:selected').size();
      $("#userSize").html(size);
      $("#userCount").show();
        } else {
      $('#allUsers option').prop('selected', false);
      $("#userCount").hide();
        }
        // $('#textbox1').val($(this).is(':checked'));        
    });

    $('#allUsers').change(function(){
      var size = $('#allUsers option:selected').size();
    $("#userSize").html(size);
    $("#userCount").show();
    });

</script>
@endsection
