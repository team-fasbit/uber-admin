<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="@if(Auth::guard('manager')->user()->picture){{Auth::guard('manager')->user()->picture}} @else {{asset('admin-css/dist/img/avatar.png')}} @endif" class="img-circle" alt="User Image" style="height:45px;">
            </div>
            <div class="pull-left info">
                <p>{{Auth::guard('manager')->user()->name}}</p>
                <a href="{{route('manager.profile')}}">{{ tr('manager') }}</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">

            <li id="dashboard">
              <a href="{{route('manager.dashboard')}}">
                <i class="fa fa-dashboard"></i> <span>{{tr('dashboard')}}</span>
              </a>

            </li>

            <!--<li id="maps">
              <a href="{{route('corporate.mapview')}}">
                <i class="fa fa-map"></i> <span>{{tr('map')}}</span>
              </a>

            </li>-->


            <!--<li class="treeview" id="providers">

                <a href="#">
                    <i class="fa fa-users"></i> <span>{{tr('providers')}}</span> <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                    <li id="add-provider"><a href="{{route('corporate.add.provider')}}"><i class="fa fa-circle-o"></i>{{tr('add_provider')}}</a></li>
                    <li id="view-provider"><a href="{{route('corporate.providers')}}"><i class="fa fa-circle-o"></i>{{tr('view_providers')}}</a></li>
                </ul>

            </li>-->

            <li id="create_request">
                <a href="{{route('manager.create_request')}}">
                    <i class="fa fa-credit-card"></i> <span>{{tr('create_request')}}</span>
                </a>
            </li>

            <li id="requests">
                <a href="{{route('manager.requests')}}">
                    <i class="fa fa-credit-card"></i> <span>Ride Requests Management</span>
                </a>
            </li>

            <!--<li class="treeview" id="rating_review">

                <a href="#">
                    <i class="fa fa-users"></i> <span>{{tr('rating_review')}}</span> <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                    <li id="user-review"><a href="{{route('corporate.user_reviews')}}"><i class="fa fa-circle-o"></i>{{tr('user_review')}}</a></li>
                    <li id="provider-review"><a href="{{route('corporate.provider_reviews')}}"><i class="fa fa-circle-o"></i>{{tr('provider_review')}}</a></li>
                </ul>

            </li>-->


            <!--<li id="payments">
                <a href="{{route('corporate.payments')}}">
                    <i class="fa fa-credit-card"></i> <span>{{tr('user_payments')}}</span>
                </a>
            </li>-->

            <li id="profile">
                <a href="{{route('manager.profile')}}">
                    <i class="fa fa-diamond"></i> <span>{{tr('account')}}</span>
                </a>
            </li>

            <li>
                <a href="{{route('manager.logout')}}">
                    <i class="fa fa-sign-out"></i> <span>{{tr('sign_out')}}</span>
                </a>
            </li>

        </ul>

    </section>

    <!-- /.sidebar -->

</aside>
