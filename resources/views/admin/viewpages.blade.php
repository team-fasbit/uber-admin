@extends('layouts.admin')

@section('title', 'Pages')

@section('content-header', 'Pages')

@section('breadcrumb')
    <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i>Home</a></li>
    <li class="active"><i class="fa fa-book"></i> Pages</li>
@endsection

@section('content')

    @include('notification.notify')

    <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">

                @if(count($view_pages) > 0)

                    <table id="example1" class="table table-bordered table-striped">

                        <thead>
                            <tr>
                              <th>#{{tr('id')}}</th>
                              <th>{{tr('heading')}}</th>
                              <th>{{tr('description')}}</th>
                              <th>{{tr('page_type')}}</th>
                              <th>{{tr('action')}}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($view_pages as $i => $page)
                    
                                <tr>
                                  <td>{{$i+1}}</td>
                                  <td>{{$page->heading}}</td>
                                  <td>{{$page->description}}</td>
                                  <td>{{$page->type}}</td>
                                  <td>
                                        <ul class="admin-action btn btn-default">
                                            <li class="dropdown">
                                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                  Action <span class="caret"></span>
                                                </a>

                                                <ul class="dropdown-menu">
                                                   
                                                    <li role="presentation">
                                                        <a role="menuitem" tabindex="-1" href="{{route('editPage', array('id' => $page->id))}}">
                                                            Edit Page
                                                        </a>
                                                    </li>

                                                    
                                                    <li role="presentation">
                                                      @if(Setting::get('admin_delete_control'))

                                                        <a role="button" href="javascript:;" class="btn disabled" style="text-align: left">{{tr('delete')}}</a>

                                                       @else
                                                        <a role="menuitem" tabindex="-1" onclick="return confirm('Are you sure?');" href="{{route('deletePage',array('id' => $page->id))}}">
                                                            Delete Page
                                                        </a>
                                                      @endif
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