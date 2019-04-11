<!DOCTYPE html>
<html>
    <head>
        <!--meta-->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{Setting::get('site_name')}}</title>

        <link href="{{asset('/web-css/welcome_note.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/web-css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('/web-css/materialdesignicons.css')}}" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Lato:300" rel="stylesheet">
        <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"/>
    
    </head>
	<style>

	</style>
    <body>
    <!--welcome-outer-->
    <div class="welcome_outer">
        <!--main_bg_section-->
        <nav class="navbar navbar-default navbar-fixed-top">
		  <div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" href="#">{{Setting::get('site_name')}}</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			  <ul class="nav navbar-nav">

			  </ul>

			  <ul class="nav navbar-nav navbar-right">
				 <li><a href="#download">Download</a></li>
				<li><a href="#mobile_features">Features</a></li>
			   <!--  <li><a href="#signin">Log In</a></li> -->
				<li><a href="#contact">Contact</a></li>
			  </ul>
			</div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>
		<section class="video-section">
			<div class="video_section_overlay"></div>
			<div class="header-content">
				<div class="inner">
					<h1 class="animated fadeInUp">ALL YOU NEED TO START YOUR ON DEMAND CAR RENTAL BUSINESS
					</h1>
					<p class="lead">Simple yet powerful software to power your sophisticated business like no other.</p>
				</div>
			</div>
			<video autoplay="true" loop="true" id="video-background">
				<source src="video/ny-traffic.mp4" type="video/mp4">Your browser does not support the video tag. I suggest you upgrade your browser.
			</video>
		</section>
		
	   <!--Features-->
	   <section class="key_features">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="theme-heading">
							<span>You’ll LOVE this</span>
							<h1>key Features</h1>
							<p>
								Essential technology ingredients to back your business technically, so you can focus on the business without any hassle.
							</p>
						</div>
					</div>
					<div class="features_listing">
						<div class="col-md-4 col-sm-6">
							<div class="features_listing_content">
								<div class="icon">
									<i class="mdi mdi-bookmark-plus mdi-48px"></i> 
									<h3>Web Sockets</h3>
									<p>
										Harness the power of web sockets to create low latency request. This mean no matter how many of your customer use the app, it takes less time to respond. The lower the better.
									</p>
								</div>
							</div>
						</div>
						<div class="col-md-4 col-sm-6">
							<div class="features_listing_content">
								<div class="icon">
									<i class="mdi mdi-bookmark-plus mdi-48px"></i> 
									<h3>Socket io -chat</h3>
									<p>
										Channel based chat system, efficient, fast and seamless. Smart car premium will uses socket io to improve your customer, service provider experience.
									</p>
								</div>
							</div>
						</div>
						<div class="col-md-4 col-sm-6">
							<div class="features_listing_content">
								<div class="icon">
									<i class="mdi mdi-bookmark-plus mdi-48px"></i> 
									<h3>Code optimization</h3>
									<p>
										At Smart Car Premium, we practise the best coding standards, We believe code is poetry. All the code is rigorously structured so there are no leakages. Period.
									</p>
								</div>
							</div>
						</div>
						<div class="col-md-4 col-sm-6">
							<div class="features_listing_content">
								<div class="icon">
									<i class="mdi mdi-bookmark-plus mdi-48px"></i> 
									<h3>Code developed in laravel 5.2</h3>
									<p>
										Smart car premium, made with all the goodness and security that the Laravel 5.2 framework has to offer and we’ve crafted it with LOVE, so your car rental business performs well.
									</p>
								</div>
							</div>
						</div>
						<div class="col-md-4 col-sm-6">
							<div class="features_listing_content">
								<div class="icon">
									<i class="mdi mdi-bookmark-plus mdi-48px"></i> 
									<h3 class="queue">Queue concept</h3>
									<p>
										Email notifications, in-app push notifications and all that jazz perfectly queued up and works like an orchestra. Timed and synced perfectly, landed in everyones respective inbox.
									</p>
								</div>
							</div>
						</div>
						<div class="col-md-4 col-sm-6">
							<div class="features_listing_content">
								<div class="icon">
									<i class="mdi mdi-bookmark-plus mdi-48px"></i> 
									<h3>Basic Analytics In Admin Panel</h3>
									<p>
										Data is key to make decisions. Website Admin has got the option to view Smartcar App users as well as the site users, and the current rides real time in the admin panel. Use it wisely.
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	   </section>

        <!--mobile_features_section-->
        <section class="mobile_featureslist_section" id="mobile_features">
            <div class="container">
                <div class="row">
					<div class="col-md-12 col-md-offset-0 col-lg-8 col-lg-offset-2">
						<div class="theme-heading">
							<span>We're expert</span>
							<h1>WEB PAGE ANALYTICS</h1>
							<p>
								With an advanced backend configuration admin can Track Reports like total number of signed up users, Recent Site look at Count , Track Drivers, examine Ratings in an attractive graphical format.
							</p>
						</div>
					</div>
					<div class="col-lg-4 col-md-4">
						<ul class="list-unstyled">
							<li>
								<p class="lead">Normal Request Flow</p>
								<p>
									A comfortable ride in less than 10 minutes, or get a driver on your door step.You may reserve for a driver at your convenience for outstation as well as local.
								</p>
							</li>
							<li>
								<p class="lead">Request Later Flow</p>
								<p>
									For users convenience&#44; The bookings can be scheduled to ensure that the user doesn&#39;t forget any rides and the booking request will be delivered to drivers appropriately at the scheduled time.
								</p>
							</li>
							<li>
								<p class="lead">Hourly Package</p>
								<p>
									Allow your customers to Hire your car on hourly basis. So they can use it for heading to multiple destination, while they save money and your business grows. It’s proportional.
								</p>
							</li>
						</ul>
					</div>
					<div class="col-lg-4 col-md-4">
						<div class="text-center  phone_img">
                            <img src="{{asset('/images/phone-img.gif')}}" alt="phone-screen" width="275" class="img-responsive"/>
                        </div>
					</div>
					<div class="col-lg-4 col-md-4">
						<ul class="list-unstyled">
							<li>
								<p class="lead">Airport Package</p>
								<p>
									We’ve revamped our Airport Package calculator based on your location, pricing specifically for Airports from your location / destination done right.
								</p>
							</li>
							<li>
								<p class="lead">Multi - Currency</p>
								<p>
									The Smart Car software comes with multi-currency support & it allows you to do multiple currency calculations – Unlimited, simultaneous use of any number of currencies.
								</p>
							</li>
							<li>
								<p class="lead">Customized Themes</p>
								<p>
									Smart car’s User and driver app are developed with best in class multi interface. App has elegant look and has an attractive design with rich user experience.
								</p>
							</li>
						</ul>
					</div>

                </div>
        </section>

		<!--web-features-->
		<section class="web-features">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="theme-heading">
							<span>Check Here</span>
							<h1>Map-view real time tracking </h1>
							<p>
								Track drivers on map view with Estimated Time of Arrival (ETA) on Smart Car premium app,
							</p>
						</div>
					</div>
						<div class="col-lg-4 col-sm-6 col-md-6">
						<div class="web-feature-card">
							<p class="lead">Corporate flow</p>
							<p>
								You can even have the option where the admin will only be add a driver to the system.
							</p>
						</div>
					</div>
					<div class="col-lg-4 col-sm-6 col-md-6">
						<div class="web-feature-card">
							<p class="lead">Multiple Services</p>
							<p>
								Add up multiple vehicle types like SUV, Sedan etc. and manage it for business in selected city.
							</p>
						</div>
					</div>
					<div class="col-lg-4 col-sm-6 col-md-6">
						<div class="web-feature-card">
							<p class="lead">Review System</p>
							<p>
								Admin can manage reviews provided by driver and customer both to assess the feedback.
							</p>
						</div>
					</div>
					<div class="col-lg-4 col-sm-6 col-md-6">
						<div class="web-feature-card">
							<p class="lead">History</p>
							<p>
								An amazing User Interface made for your drivers and customers to check a brief history of rides by navigating to the ride history tab to check complete details.
							</p>
						</div>
					</div>
					<div class="col-lg-4 col-sm-6 col-md-6">
						<div class="web-feature-card">
							<p class="lead">WEB PAGE ANALYTICS</p>
							<p>
								With an advanced backend configuration admin can Track Reports like total number of signed up users, Recent Site look at Count , Track Drivers, examine Ratings in an attractive graphical format.
							</p>
						</div>
					</div>
					<div class="col-lg-4 col-sm-6 col-md-6">
						<div class="web-feature-card">
							<p class="lead">Map-view real time tracking</p>
							<p>
								Track drivers on map view with Estimated Time of Arrival (ETA) on Smart Car premium app.
							</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!--Admin_layout-->
		<section class="Admin_layout">
			<div class="container">
				<div class="row">
					<div class="col-md-12 col-md-offset-0 col-lg-8 col-lg-offset-2">
					<div class="theme-heading">
						<h1>Admin Panel Console</h1>
						<p>
							Get behind the console and feel what it’s like to control the most powerful car rental software yet.
						</p>
						<a target="_blank" href="{{asset('/admin/login')}}" class="btn btn-check">Check out Here</a>
					</div>
				</div>
				</div>
			</div>
		</section>

		<!--View-demo-section-->
		<section class="View-demo-section" id="download">
			<div class="view-demo-overlay"></div>
			<div class="container">
				<div class="col-md-12 col-md-offset-0 col-lg-8 col-lg-offset-2">
					<div class="theme-heading">
						<span>Check Here</span>
						<h1>View Demo</h1>
						<p class="lead">
						Seeing is believing, we want our app to do the talking. Have a look at our demos by downloading it from the app store. 
						</p>
					</div>
				</div>
				<div class="col-lg-offset-3 col-lg-6">
					<div class="user_app text-center">
						<p class="lead">User App</p>
						<a href="https://play.google.com/store/apps/details?id=com.nikola.user" target="_blank">
							<img src="{{asset('/images/google_play.png')}}" alt="" />
						</a>
						<a href="https://itunes.apple.com/us/app/nikola-passenger-app/id1290774324?ls=1&mt=8" style="cursor: pointer;" target="_blank">
							<img src="{{asset('/images/istore.png')}}" alt="" />
						</a>
					</div>
					<div class="user_app text-center">
						<p class="lead">Driver App</p>
						<a href="https://play.google.com/store/apps/details?id=com.nikola.driver" target="_blank">
							<img src="{{asset('/images/google_play.png')}}" alt="" />
						</a>
						<a href="https://itunes.apple.com/us/app/nikola-driver-app/id1290774335?ls=1&mt=8" target="_blank">
							<img src="{{asset('/images/istore.png')}}" alt="" />
						</a>
					</div>
				</div>
			</div>
		</section>


		<!--Admin_layout-->
		<section class="Admin_layout">
			<div class="container">
				<div class="row">
					<div class="col-md-12 col-md-offset-0 col-lg-8 col-lg-offset-2">
					<div class="theme-heading">
						<h1>Partner Console</h1>
						<p>
							Run a Private / Whole owned Fleet business with only your cars powered by CORPORATE MODE
						</p>
						<a href="{{asset('corporate/login')}}" class="btn btn-check" target="_blank">Check out Here</a>
					</div>
				</div>
				</div>
			</div>
		</section>

        <!--fact_section-->
		<section class="fact_section">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div class="theme-heading">
							<span>Enjoy It</span>
							<h1>Fun Fact</h1>
							<p>
								It’s takes about 4380 Man hours to build a Premium Car Rental software like Smart Car.
							</p>
						</div>
					</div>
					<div class="col-md-3 col-sm-6">
						<div class="feature-center text-center">
							<span class="icon">
								<i class="ti-download"></i>
							</span>
							<span class="counter"><span class="js-counter" data-from="0" data-to="15" data-speed="1500" data-refresh-interval="50">12K</span>+</span>
							<span class="counter-label">Downloads</span>

						</div>
					</div>
					<div class="col-md-3 col-sm-6">
						<div class="feature-center text-center">
							<span class="icon">
								<i class="ti-download"></i>
							</span>
							<span class="counter"><span class="js-counter" data-from="0" data-to="15" data-speed="1500" data-refresh-interval="50">1500</span>+</span>
							<span class="counter-label">Customizations</span>

						</div>
					</div>
					<div class="col-md-3 col-sm-6">
						<div class="feature-center text-center">
							<span class="icon">
								<i class="ti-download"></i>
							</span>
							<span class="counter"><span class="js-counter" data-from="0" data-to="15" data-speed="1500" data-refresh-interval="50">4K</span>+</span>
							<span class="counter-label">Hours Spent</span>

						</div>
					</div>
					<div class="col-md-3 col-sm-6">
						<div class="feature-center text-center">
							<span class="icon">
								<i class="ti-download"></i>
							</span>
							<span class="counter"><span class="js-counter" data-from="0" data-to="15" data-speed="1500" data-refresh-interval="50">15</span>+</span>
							<span class="counter-label">Developers</span>

						</div>
					</div>
				</div>
			</div>
		</section>

        <!--footer-section-->
        <footer id="contact">
            <div class="container">
                <div class="col-lg-4 col-md-4 col-xs-12">
                    <div class="about_us">
                        <h3 class="section_title">About Us</h3>
                        <p>
                           Advanced car rental software that has all the features of UBER, Has fleet management, Vehicle hire, multiple payment gateways. Comes with Mobile apps for IOS and Android.
                        </p>
                        <h3 class="section_title"></h3>
                        <p class="copy_right">
                            All rights reserved Copyright © 2016 <br/> Smart Car: <a href="http://smart-car.tech/car-rental-software/" target="_blank" >Car Rental Software</a>
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12">
                    <div class="contact_address">
                        <h3 class="section_title">Our Address</h3>
                        <ul class="contact_info list-unstyled">
                            <li>
                                <i class="fa fa-location-arrow"></i>1980 Post Oak Blvd,
                                 Houston,<br/> Texas 77056.

                            </li>
                            <li>
                                <i class="fa fa-phone"></i>
                                Ph: (888) 884-3777
                            </li>
                            <li>
                                <i class="fa fa-envelope"></i>
                                info@smart-car.tech
                            </li>
                        </ul>
                        <h3 class="section_title">Connect with us</h3>
                        <ul class="social_media list-unstyled">
                            <li>
                                <a href="javascript:void(0);">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <i class="fa fa-dribbble"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <i class="fa fa-github-alt"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12">
                    <div class="comment_section">
                        <h3 class="section_title">Drop us a line</h3>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Name" />
                        </div>
                         <div class="form-group">
                            <input type="email" class="form-control" placeholder="Email" />
                        </div>
                         <div class="form-group">
                            <textarea class="form-control" rows="6" placeholder="Message"></textarea>
                        </div>
                        <div class="form-group clearfix">
                            <button type="sumbit" class="btn pull-right">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
      <a href="javascript:void(0);" id="scroll" title="Scroll to Top" style="display: none;">
		<i class="mdi mdi-arrow-up mdi-18px"></i>
	 </a>
    </div>

    </body>
    <!--js-->
    <script src="{{asset('/admin-css/bootstrap/js/jquery.min.js')}}"></script>
    <script src="{{asset('/admin-css/bootstrap/js/bootstrap.min.js')}}"></script>
     <!--scrolltop & navbar-->
     <script type='text/javascript'>
    $(document).ready(function(){
        $(window).scroll(function(){
            if ($(this).scrollTop() > 100) {
                $('#scroll').fadeIn();
                $('.navbar-default').css("background-color" , "#fff");
                $('.navbar-default').css("box-shadow", "rgba(0, 0, 0, 0.4) 0px 2px 1px 0px");
                $('.navbar-default').css("border-bottom", "1px solid rgb(221, 221, 221");
                $('.navbar-brand').css("color","#000");
                $('.navbar-nav>li>a').css("color","#000");
                $('.welcome_outer .navbar-default .navbar-brand:hover').css("color","#000");
				$('.navbar-default .navbar-toggle .icon-bar').css("background-color","#000");
            } else {
                $('#scroll').fadeOut();
                $('.navbar-default').css("background-color", "transparent");
                $('.navbar-brand').css("color","#fff");
                $('.navbar-nav>li>a').css("color","#fff");
                $('.navbar-default').css("box-shadow", "none");
                $('.navbar-default').css("border-bottom", "none");
                $('.welcome_outer .navbar-default .navbar-brand:hover').css("color","#fff");
				$('.navbar-default .navbar-toggle .icon-bar').css("background-color","#fff");
            }
        });
        $('#scroll').click(function(){
            $("html, body").animate({ scrollTop: 0 }, 1000);
            return false;
        });
    });
    </script>
	<script>
	
$( "a" ).click(function( event ) {
  event.preventDefault();
}
	</script>
<script>
$(document).ready(function(){

  $("a").on('click', function(event) {
    if (this.hash !== "") {
      event.preventDefault();
      var hash = this.hash;
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 800, function(){

        window.location.hash = hash;
      });
    }
  });
});
</script>

</html>