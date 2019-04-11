<?php 
  use App\Admin; 
  $user = Admin::find(Auth::guard('admin')->user()->id);
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="@if(Auth::guard('admin')->user()->picture){{Auth::guard('admin')->user()->picture}} @else {{asset('admin-css/dist/img/avatar.png')}} @endif" class="img-circle" alt="User Image" style="height:45px;">
            </div>
            <div class="pull-left info">
                <p>{{Auth::guard('admin')->user()->name}}</p>
                <a href="{{route('admin.profile')}}">{{ tr('admin') }}</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">

            <li id="dashboard">
              <a href="{{route('admin.dashboard')}}">
                <i class="fa fa-dashboard"></i> <span>{{tr('dashboard')}}</span>
              </a>

            </li>


<!-- Status on map -->
          
            <?php if(($user->booking_stats !='' && $user->booking_stats !=0 ) ||($user->driver_availability_stats !='' && $user->driver_availability_stats !=0)){ ?>
            <li class="treeview" id="maps">

                <a href="#">
                    <i class="fa fa-map"></i> <span>{{tr('map')}}</span> <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                  <?php if($user->booking_stats !='' && $user->booking_stats !=0 && in_array(VIEW_ALL, explode(',', $user->booking_stats))){ ?>

                    <li id="user-map"><a href="{{route('admin.usermapview')}}"><i class="fa fa-circle-o"></i>Booking Stats</a></li>

                    <?php } 
                    if($user->driver_availability_stats !='' && $user->driver_availability_stats !=0 && in_array(VIEW_ALL, explode(',', $user->driver_availability_stats))){?>

                    <li id="provider-map"><a href="{{route('admin.mapview')}}"><i class="fa fa-circle-o"></i>Driver availability Stats </a></li>
                    <?php } ?>
                </ul>

            </li>
            <?php } ?>

<!-- Corporate's under you  -->
        <?php if($user->corporates !='' && $user->corporates !=0){ ?>

            <li class="treeview" id="corporates">

                <a href="#">
                    <i class="fa fa-users"></i> <span>Corporate's under you</span> <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                    <?php if($user->corporates !='' && $user->corporates !=0 && in_array(ADD, explode(',', $user->corporates))){ ?>
                      <li id="add-corporate"><a href="{{route('admin.add.corporate')}}"><i class="fa fa-circle-o"></i>Add a Corporate</a></li>
                    <?php }

                    if($user->corporates !='' && $user->corporates !=0 && in_array(VIEW_ALL, explode(',', $user->corporates))){ ?>
                      <li id="view-corporate"><a href="{{route('admin.corporates')}}"><i class="fa fa-circle-o"></i>{{tr('view_corporates')}}</a></li>
                    <?php } ?>
                </ul>

            </li>

            <?php } ?>


<!-- Call Center Management -->
<?php if($user->call_center_managers !='' && $user->call_center_managers !=0){ ?>

            <li class="treeview" id="call_centers">
                <a href="#">
                    <i class="fa fa-users"></i> <span>Call Center Management</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">

                    <?php if($user->call_center_managers !='' && $user->call_center_managers !=0 && in_array(ADD, explode(',', $user->call_center_managers))){ ?>
                    <li id="add-manager"><a href="{{route('admin.add.manager')}}"><i class="fa fa-circle-o"></i>Add a Manager</a></li>
                    <?php }?>

                    <?php if($user->call_center_managers !='' && $user->call_center_managers !=0 && in_array(VIEW_ALL, explode(',', $user->call_center_managers))){ ?>
                    <li id="view-manager"><a href="{{route('admin.managers')}}"><i class="fa fa-circle-o"></i>{{tr('view_managers')}}</a></li>
                    <?php }?>
                </ul>

            </li>
<?php } ?>

<!-- Passenger Management(users) -->
          <?php if($user->users !='' && $user->users !=0){ ?>

            <li class="treeview" id="users">

                <a href="#">
                    <i class="fa fa-user"></i> <span>Passenger Management</span> <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                    <?php if($user->users !='' && $user->users !=0 && in_array(ADD, explode(',', $user->users))){ ?>
                      <li id="add-user"><a href="{{route('admin.add.user')}}"><i class="fa fa-circle-o"></i>Add a passenger</a></li>
                    <?php } 

                     if($user->users !='' && $user->users !=0 && in_array(VIEW_ALL, explode(',', $user->users))){ ?>
                      <li id="view-user"><a href="{{route('admin.users')}}"><i class="fa fa-circle-o"></i>View all Passengers</a></li>
                    <?php } ?>
                </ul>

            </li>
            <?php } ?>



<!-- Driver Management(providers) -->
          <?php if($user->providers !='' && $user->providers !=0){ ?>
            <li class="treeview" id="providers">

                <a href="#">
                    <i class="fa fa-users"></i> <span>Driver Management</span> <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">

                  <?php if($user->providers !='' && $user->providers !=0 && in_array(ADD, explode(',', $user->providers))){ ?>
                      <li id="add-provider"><a href="{{route('admin.add.provider')}}"><i class="fa fa-circle-o"></i>Add a Driver</a></li>
                    <?php } 

                    if($user->providers !='' && $user->providers !=0 && in_array(VIEW_ALL, explode(',', $user->providers))){ ?>
                      <li id="view-provider"><a href="{{route('admin.providers')}}"><i class="fa fa-circle-o"></i>View all Drivers</a></li>
                    <?php } ?>

                </ul>

            </li>
            <?php } ?>

<!-- sub-admins -->
          <?php if($user->sub_admins !='' && $user->sub_admins !=0){ ?>

            <li class="treeview" id="sub_admins">

                <a href="#">
                    <i class="fa fa-user"></i> <span>Sub Admin Management</span> <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                  <?php if($user->sub_admins !='' && $user->sub_admins !=0 && in_array(ADD, explode(',', $user->sub_admins))){ ?>
                      <li id="add-sub_admin"><a href="{{route('admin.add.sub_admin')}}"><i class="fa fa-circle-o"></i>Add a Sub admin</a></li>
                  <?php } 

                  if($user->sub_admins !='' && $user->sub_admins !=0 && in_array(VIEW_ALL, explode(',', $user->sub_admins))){ 
                    ?>
                    <li id="view-sub_admin"><a href="{{route('admin.sub_admins')}}"><i class="fa fa-circle-o"></i>View all Sub admins</a></li>
                  <?php }?>
                </ul>

            </li>

            <?php } ?>


<!-- Ride Requests Management -->
          <?php if($user->ride_requests_management !='' && $user->ride_requests_management !=0){?>
              <li id="requests">
                  <a href="{{route('admin.requests')}}">
                      <i class="fa fa-credit-card"></i> <span>Ride Requests Management</span>
                  </a>
              </li>

            <?php } ?>


<!-- Service Types(Vehicle Types) -->
          <?php if($user->vehicle_types !='' && $user->vehicle_types !=0){ ?>
            <li class="treeview" id="service_types">

                <a href="#">
                    <i class="fa fa-users"></i> <span>{{tr('service_types')}}</span> <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                  <?php if($user->vehicle_types !='' && $user->vehicle_types !=0 && in_array(ADD, explode(',', $user->vehicle_types))){ ?>
                    <li id="add-service"><a href="{{route('admin.add.service.type')}}"><i class="fa fa-circle-o"></i>Add a Vehicle Type</a></li>
                  <?php } 

                  if($user->vehicle_types !='' && $user->vehicle_types !=0 && in_array(VIEW_ALL, explode(',', $user->vehicle_types))){  ?>
                    <li id="view-service"><a href="{{route('admin.service.types')}}"><i class="fa fa-circle-o"></i>View all Vehicle Types</a></li>
                  <?php } ?>
                </ul>

            </li>
          <?php } ?>


<!-- Promo Codes -->
          <?php if($user->promo_codes !='' && $user->promo_codes !=0){ ?>
            <li class="treeview" id="promo_codes">

                <a href="#">
                    <i class="fa fa-users"></i> <span>Promo Codes</span> <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                  <?php if($user->promo_codes !='' && $user->promo_codes !=0 && in_array(ADD, explode(',', $user->promo_codes))){ ?>
                    <li id="add-promocode"><a href="{{route('admin.add.promo_code')}}"><i class="fa fa-circle-o"></i>Add a Promo Code</a></li>
                  <?php } 

                  if($user->promo_codes !='' && $user->promo_codes !=0 && in_array(VIEW_ALL, explode(',', $user->promo_codes))){ ?>
                    <li id="view-promocode"><a href="{{route('admin.promo_codes')}}"><i class="fa fa-circle-o"></i>View all Promo Codes</a></li>
                  <?php }?>
                </ul>

            </li>

          <?php } ?>


<!-- Rentals Management -->

          <?php if($user->rental_management !='' && $user->rental_management !=0){ ?>

            <li class="treeview" id="hourly_packages">

                <a href="#">
                    <i class="fa fa-users"></i> <span>Rentals Management</span> <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                  <?php if($user->rental_management !='' && $user->rental_management !=0 && in_array(ADD, explode(',', $user->rental_management))){ ?> 
                     <li id="add-hourly_package"><a href="{{route('admin.add.hourly_package')}}"><i class="fa fa-circle-o"></i>Add a Hourly Package</a></li>
                  <?php }

                  if($user->rental_management !='' && $user->rental_management !=0 && in_array(VIEW_ALL, explode(',', $user->rental_management))){ ?>
                    <li id="view-hourly_package"><a href="{{route('admin.hourly_package')}}"><i class="fa fa-circle-o"></i>View all the packages</a></li>
                  <?php } ?>
                </ul>

            </li>
          <?php } ?>

          <!--    <li class="treeview" id="airport">
              <a href="#">
                <i class="fa fa-share"></i> <span>Airport Package</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu menu-open" style="display: block;">
                <li id="airport-details">
                  <a href="#"><i class="fa fa-circle-o"></i> Airport Details
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu menu-open" style="display: none;">
                    <li><a href="{{route('admin.airport_detail.add')}}"><i class="fa fa-circle-o"></i> Add Airport Detail</a></li>
                    <li><a href="{{route('admin.airport_details')}}"><i class="fa fa-circle-o"></i> View Airport Detail</a></li>

                  </ul>
                </li>
                <li id="location-details">
                  <a href="#"><i class="fa fa-circle-o"></i> Location Details
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu menu-open" style="display: none;">
                    <li><a href="{{route('admin.location_detail.add')}}"><i class="fa fa-circle-o"></i> Add Location Detail</a></li>
                    <li><a href="{{route('admin.location_details')}}"><i class="fa fa-circle-o"></i> View Location Detail</a></li>

                  </ul>
                </li>
                <li id="airport-pricing">
                  <a href="#"><i class="fa fa-circle-o"></i> Airport Pricing
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu menu-open" style="display: none;">
                    <li><a href="{{route('admin.airport_pricing.add')}}"><i class="fa fa-circle-o"></i> Add Airport Price</a></li>
                    <li><a href="{{route('admin.airport_pricings')}}"><i class="fa fa-circle-o"></i> View Airport Price</a></li>

                  </ul>
                </li>
              </ul>
            </li> -->


<!-- Airport rides -->
            <?php if(($user->airport_details !='' && $user->airport_details !=0) || ($user->destination_details !='' && $user->destination_details !=0) || ($user->pricing_management !='' && $user->pricing_management !=0)){ ?>

              <li class="treeview " id="airport" >
                    <a href="#"><i class="fa fa-circle-o"></i> Airport rides
                      <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                      </span>
                    </a>
                    <ul class="treeview-menu" style="display: none;">

                      <!-- Airport Details -->
                      <?php if($user->airport_details !='' && $user->airport_details !=0){ ?>
                        <li id="airport-details" class="active">
                          <a href="#"><i class="fa fa-circle-o"></i> Airport Details
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                          </a>
                          <ul class="treeview-menu active">
                          <?php if($user->airport_details !='' && $user->airport_details !=0 && in_array(ADD, explode(',', $user->airport_details))){ 
                            ?>
                              <li class="active" ><a href="{{route('admin.airport_detail.add')}}"><i class="fa fa-circle-o"></i> Add an Airport</a></li>
                          <?php } ?>

                          <?php if($user->airport_details !='' && $user->airport_details !=0 && in_array(VIEW_ALL, explode(',', $user->airport_details))){ 
                            ?>
                              <li><a href="{{route('admin.airport_details')}}"><i class="fa fa-circle-o"></i> View all Airports</a></li>
                          <?php } ?>

                          </ul>
                        </li>
                      <?php } ?>

                      <!-- Destination details -->
                      <?php if($user->destination_details !='' && $user->destination_details !=0){ ?>
                        <li id="location-details">
                          <a href="#"><i class="fa fa-circle-o"></i> Destination details
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                          </a>
                          <ul class="treeview-menu">
                          <?php if($user->destination_details !='' && $user->destination_details !=0 && in_array(ADD, explode(',', $user->destination_details))){ ?>
                            <li><a href="{{route('admin.location_detail.add')}}"><i class="fa fa-circle-o"></i>Add a destination</a></li>
                          <?php } ?>

                          <?php if($user->destination_details !='' && $user->destination_details !=0 && in_array(VIEW_ALL, explode(',', $user->destination_details))){ ?>
                            <li><a href="{{route('admin.location_details')}}"><i class="fa fa-circle-o"></i>View all Destination's</a></li>
                          <?php } ?>
                          </ul>
                        </li>
                      <?php } ?>

                    <!-- Pricing Management -->
                    <?php if($user->pricing_management !='' && $user->pricing_management !=0){ ?>
                        <li id="airport-pricing">
                          <a href="#"><i class="fa fa-circle-o"></i> Pricing Management
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                          </a>
                          <ul class="treeview-menu">
                            <?php if($user->pricing_management !='' && $user->pricing_management !=0 && in_array(ADD, explode(',', $user->pricing_management))){ ?>
                              <li><a href="{{route('admin.airport_pricing.add')}}"><i class="fa fa-circle-o"></i>Pricing setup</a></li>
                            <?php } 

                            if($user->pricing_management !='' && $user->pricing_management !=0 && in_array(VIEW_ALL, explode(',', $user->pricing_management))){ ?>
                              <li><a href="{{route('admin.airport_pricings')}}"><i class="fa fa-circle-o"></i>View all Pricing plans</a></li>
                            <?php } ?>

                          </ul>
                        <?php } ?>

                      </li>
                    </ul>
              </li>
            <?php } ?>

            <!-- <li class="treeview">
              <a href="#">
                <i class="fa fa-share"></i> <span>Multilevel</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu menu-open" style="display: none;">
                <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                <li>
                  <a href="#"><i class="fa fa-circle-o"></i> Level One
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                    <li>
                      <a href="#"><i class="fa fa-circle-o"></i> Level Two
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>
                      <ul class="treeview-menu">
                        <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                        <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                      </ul>
                    </li>
                  </ul>
                </li>
                <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
              </ul>
            </li>  -->

<!-- Ratings -->
          <?php if(($user->provider_ratings !='' && $user->provider_ratings !=0) || ($user->user_ratings !='' && $user->user_ratings !=0)){ ?>
              <li class="treeview" id="rating_review">

                  <a href="#">
                      <i class="fa fa-users"></i> <span>Ratings</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>             
                  <ul class="treeview-menu">
                    <?php if($user->user_ratings !='' && $user->user_ratings !=0 && in_array(VIEW_ALL, explode(',', $user->user_ratings))){ ?>
                      <li id="user-review"><a href="{{route('admin.user_reviews')}}"><i class="fa fa-circle-o"></i>Passenger Ratings</a></li>
                    <?php } 

                    if($user->provider_ratings !='' && $user->provider_ratings !=0 && in_array(VIEW_ALL, explode(',', $user->provider_ratings))){?>
                      <li id="provider-review"><a href="{{route('admin.provider_reviews')}}"><i class="fa fa-circle-o"></i>Driver Ratings</a></li>
                    <?php } ?>
                  </ul>

              </li>
          <?php } ?>


<!-- Documents Management -->
          <?php if($user->documents_management !='' && $user->documents_management !=0){ ?>
              <li class="treeview" id="documents">

                  <a href="#">
                      <i class="fa fa-users"></i> <span>Documents Management</span> <i class="fa fa-angle-left pull-right"></i>
                  </a>

                  <ul class="treeview-menu">
                    <?php if($user->documents_management !='' && $user->documents_management !=0 && in_array(ADD, explode(',', $user->documents_management))){ ?>
                        <li id="add-document"><a href="{{route('admin.add_document')}}"><i class="fa fa-circle-o"></i>Add a Document Type</a></li>
                    <?php } 

                    if($user->documents_management !='' && $user->documents_management !=0 && in_array(VIEW_ALL, explode(',', $user->documents_management))){ ?>
                      <li id="view-document"><a href="{{route('admin.documents')}}"><i class="fa fa-circle-o"></i>View all Document Types</a></li>
                    <?php } ?>
                  </ul>

              </li>
          <?php } ?>

<!-- Currency Management -->
          <?php if($user->currency_management !='' && $user->currency_management !=0){ ?>
            <li class="treeview" id="currency">

                <a href="#">
                    <i class="fa fa-users"></i> <span>Currency Management</span> <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                  <?php if($user->currency_management !='' && $user->currency_management !=0 && in_array(ADD, explode(',', $user->currency_management))){ ?>
                      <li id="add-currency"><a href="{{route('admin.add_currency')}}"><i class="fa fa-circle-o"></i>Add Currency</a></li>
                  <?php } ?>

                  <?php if($user->currency_management !='' && $user->currency_management !=0 && in_array(VIEW_ALL, explode(',', $user->currency_management))){ ?>
                    <li id="view-currency"><a href="{{route('admin.currency')}}"><i class="fa fa-circle-o"></i>View all Currencies</a></li>
                  <?php } ?>
                </ul>

            </li>
          <?php } ?>

<!-- Cancellation Reason Management -->
        <li class="treeview" id="cancellation_reasons">

            <a href="#">
                <i class="fa fa-users"></i> <span>Cancellation Reasons</span> <i class="fa fa-angle-left pull-right"></i>
            </a>

            <ul class="treeview-menu">
                <li id="add-reasons"><a href="{{route('admin.add_cancellation_reason')}}"><i class="fa fa-circle-o"></i>Add Cancellation Reason</a></li>

                <li id="view-reasons"><a href="{{route('admin.cancellation_reasons')}}"><i class="fa fa-circle-o"></i>View All Reasons</a></li>
            </ul>

        </li>

<!-- Transactions -->
          <?php 
          if($user->transactions !='' && $user->transactions !=0){
          ?>
            <li id="payments">
                <a href="{{route('admin.payments')}}">
                    <i class="fa fa-credit-card"></i> <span>Transactions</span>
                </a>
            </li>
          <?php } ?>


<!-- Push Notifications -->
          <?php if($user->push_notifications !='' && $user->push_notifications !=0){ ?>
            <li id="push_notifications">
                <a href="{{route('admin.push_notifications')}}">
                    <i class="fa fa-gears"></i> <span>Push Notifications</span>
                </a>
            </li>
          <?php } ?>

<!-- Settings-->
<?php if($user->settings !='' && $user->settings !=0){ ?>
            <li id="settings">
                <a href="{{route('admin.settings')}}">
                    <i class="fa fa-gears"></i> <span>{{tr('settings')}}</span>
                </a>
            </li>
<?php } ?>

<?php if($user->settings !='' && $user->settings !=0){ ?>
            <li id="tron_settings">
                <a href="{{route('admin.show.tron')}}">
                    <i class="fa fa-google-wallet"></i> <span>Tron Wallet</span>
                </a>
            </li>
<?php } ?>

<!-- Advertisement -->
<?php if($user->ads_management !='' && $user->ads_management !=0){ ?>
            <li class="treeview" id="ads">

                <a href="#">
                    <i class="fa fa-users"></i> <span>Ads Management</span> <i class="fa fa-angle-left pull-right"></i>
                </a>

                <ul class="treeview-menu">
                  <?php if($user->ads_management !='' && $user->ads_management !=0 && in_array(ADD, explode(',', $user->ads_management))){ ?>
                      <li id="add-ads"><a href="{{route('admin.add_ads')}}"><i class="fa fa-circle-o"></i>Add Ads</a></li>
                  <?php } ?>

                  <?php if($user->ads_management !='' && $user->ads_management !=0 && in_array(VIEW_ALL, explode(',', $user->ads_management))){ ?>
                    <li id="view-ads"><a href="{{route('admin.ads')}}"><i class="fa fa-circle-o"></i>View all Ads</a></li>
                  <?php } ?>
                </ul>

            </li>
<?php } ?>

            <li id="profile">
                <a href="{{route('admin.profile')}}">
                    <i class="fa fa-diamond"></i> <span>{{tr('account')}}</span>
                </a>
            </li>

            <li>
                <a href="{{route('admin.logout')}}">
                    <i class="fa fa-sign-out"></i> <span>{{tr('sign_out')}}</span>
                </a>
            </li>

        </ul>

    </section>

    <!-- /.sidebar -->

</aside>
