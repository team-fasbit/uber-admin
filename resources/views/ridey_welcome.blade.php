<!DOCTYPE html>
<html>
<head>
  <!--meta-->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RIDEY</title>
  <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/Juno_clone/images/favicon-16x16.png')}}">
  <link href="{{asset('/Juno_clone/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Droid+Serif" rel="stylesheet">
  <link href="{{asset('/Juno_clone/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('/Juno_clone/css/style.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('/Juno_clone/css/animate.css')}}" rel="stylesheet" type="text/css" />

</head>
<style>

</style>
<body>
  <!--welcome-outer-->
  <div class="welcome_outer">
    <!--main_bg_section-->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <a class="navbar-brand" href="/">RIDEY</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">

          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#own">Become a driver</a></li>

          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
    <!--********************video-section*********************-->
    <section class="video-section">
      <div class="video_section_overlay"></div>
      <div class="header-content">
        <div class="inner">
          <h1 class="animated fadeInUp">RIDEY TREATS DRIVERS BETTER, DRIVERS TREAT YOU BETTER.
          </h1>
          <p class="lead wow fadeInUp" data-wow-delay=".9s">A New Approach To Taxi Cab Services.</p>
          <span class="wow fadeInUp" data-wow-delay="2s">
            <a href="{{ env('GOOGLE_STORE_USER') }}" target="_blank">
              <img src="{{asset('/Juno_clone/images/google_play.png')}}" />
            </a>
            <a href="{{ env('IOS_USER') }}" target="_blank">
              <img src="{{asset('/Juno_clone/images/istore.png')}}" />
            </a>
          </span>
        </div>
      </div>
      <video autoplay="true" loop="true" id="video-background" >
        <source type="video/webm" id="webm" src="{{asset('/Juno_clone/video/bg-video.webm')}}">
          <source type="video/mp4" id="mp4" src="{{asset('/Juno_clone/video/back.mp4')}}">
            Your browser does not support the video tag. I suggest you upgrade your browser.
          </video>
          <a href="#feature-section" class="down_arrow ">
            <svg width="22" height="14" viewBox="0 0 22 14" xmlns="http://www.w3.org/2000/svg"><path d="M21 1L11 11 1 1" stroke-width="3" stroke="#FFF" fill="none" fill-rule="evenodd" opacity=".8"></path></svg>
          </a>
        </section>
        <!--********************video-section*********************-->

        <!--********************Feature-section*********************-->
        <section class="Feature-section" id="feature-section">
          <div class="container">
            <div class="row">
              <h2 class="text-center">THE SOCIALLY RESPONSIBLE WAY TO RIDE</h2>
              <p class="lead">We built ridey around the belief that when people are treated better, they provide better service. Happy drivers, happy riders.</p>
              <div class="feature-outer">
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="feature-list text-center wow fadeInUp">
                    <div class="feature-icon">
                      <i class="fa fa-diamond fa-2x"></i>
                    </div>
                    <div class="feature-content">
                      <h3>Only The Best</h3>
                      <p>Ridey only accepts the highest rated drivers</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="feature-list text-center  wow fadeInUp" data-wow-delay=".15s">
                    <div class="feature-icon">
                      <i class="fa fa-gift fa-2x"></i>
                    </div>
                    <div class="feature-content">
                      <h3>Real Time Tracking</h3>
                      <p>Track Your Ride In Real Time Know How Far Your Driver is</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="feature-list text-center  wow fadeInUp" data-wow-delay=".3s">
                    <div class="feature-icon">
                      <i class="fa fa-star-o fa-2x"></i>
                    </div>
                    <div class="feature-content">
                      <h3>Rate Your Ride</h3>
                      <p>Help Us Improve Your Ridey Experience</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                  <div class="feature-list text-center  wow fadeInUp" data-wow-delay=".45s">
                    <div class="feature-icon">
                      <i class="fa fa-commenting-o fa-2x"></i>
                    </div>
                    <div class="feature-content">
                      <h3>Here For You</h3>
                      <p>24/7 live phone, email and text support</p>
                    </div>
                  </div>
                </div>
              </div><!--feature-outer-->
            </div>
          </div>
        </section>
        <!--********************Feature-section*********************-->

        <!--********************Testimonals-section*********************-->
        <section class="testimonals-section">
          <div class="container">
            <div class="row">
              <h2>A FAIR APPROACH TO TAXI CAB SERVICE</h2>
              <p class="lead">Ridey charges drivers Fair commissions than the competition, so you know your dollars are going to the right place - your driver !</p>
              <div class="testimonal-img wow fadeInUp">
                <span>
                  <img src="{{asset('/Juno_clone/images/testimonal-user.jpg')}}" alt="img" class="img-circle" />
                  <img src="{{asset('/Juno_clone/images/testimonal-car.jpg')}}" alt="img" class="img-circle" />
                  <span class="mini-circle"></span>
                </span>
                <p>
                <span class="lead">Mike Lane 4.9</span>
                <img src="{{asset('/Juno_clone/images/rating.png')}}" alt="img" width="120"/>
              </p>
                <!-- <img src="images/driver-panel.png" alt="img" width="260"/> -->
              </div>
            </div>
          </div>
        </section>
        <!--********************Testimonals-section*********************-->

        <!--********************City-card-section*********************-->
        <section class="city-card" id="own">
          <div class="container">
            <div class="row">
              <div class="card">
                <div class="card-body">
                  <h3>YOU ABOVE 21 AND LIKE TO DRIVE?</h3>
                  <p class="lead">
                    Driving with Ridey is an easy way to earn money whenever you want. We offer a true partnership.
                  </p>
                  <a href="#contact"><button type="button" class="btn wow tada"  data-wow-iteration="2">Contact Us Now</button></a>
                </div>
                <!-- <div class="card-footer">
                  <a href="#">Driving elsewhere in the US?</a>
                </div> -->
              </div>
              <div class="card">
                <div class="card-body">
                  <h3>NEED A RIDE IN MINUTES?</h3>
                  <p class="lead">
                    Enter your phone number and we’ll send you a link to download the app
                  </p>
                  @if(Session::has('flash_success'))
                      <div class="alert alert-success"  >
                          <button type="button" class="close" data-dismiss="alert">×</button>
                          {{Session::get('flash_success')}}
                      </div>
                  @endif
                  <form class="joining-form" method="POST" action="{{ url('/send_app_link') }}">
                    {{ csrf_field() }}
                    <div class="row">
                      <div class="col-md-8 form-group">

                        <input type="text" class="form-control" required name="mobile" placeholder="Your Mobile Number"/>
                        <span class="form-element__phone-code">+1</span>
                      </div>

                      <button type="submit" class="btn col-md-4 wow tada"  data-wow-iteration="2" data-wow-delay="2s">Join</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer">
                  Download now from the <a href="#">App store</a> or <a href="#">Play Store</a>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>
      <!--********************City-card-section*********************-->

      <!--********************Footer*********************-->
      <footer>
        <div class="footer-main">
          <div class="footer-main-mbl hidden-lg">
              <div class="logo_outer">
                  <span>Ridey</span>
                  <button type="button" class="btn btn-app pull-right">Get the App</button>
              </div>
              <div class="contact_us_mbl">
                  <p class="lead">CONTACT US</p>
                  <div class="row">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                          <div class="driver-info">
                      <p>Drivers</p>
                      <p>
                        855-432-RIDE
                      </p>
                      <p>drivers@goridey.com</p>
                    </div>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                          <div class="rider-info">
                      <p>Riders</p>
                      <p>
                        855-432-RIDE
                      </p>
                      <p>help@goridey.com</p>
                    </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="footer-main-desktop hidden-xs hidden-sm hidden-md">
          <div class="row">
            <div class="col-md-2">
              <div class="logo-name text-left">
                <p class="lead">Goridey</p>
              </div>
            </div>
            <div class="col-md-7">
              <div class="contact_address">
                <p>CONTACT US</p>
                <div class="row">
                  <div class="col-md-3">
                    <div class="driver-info">
                      <p>Drivers</p>
                      <p>
                        855-432-RIDE
                      </p>
                      <p>drivers@goridey.com</p>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="rider-info">
                      <p>Riders</p>
                      <p>
                        855-432-RIDE
                      </p>
                      <p>help@goridey.com</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="app-lins text-right">
                <p>
                  <a href="{{ env('GOOGLE_STORE_USER') }}" target="_blank">
                    <img src="{{asset('/Juno_clone/images/google_play.png')}}" alt="images" width="160"/>
                  </a>
                </p>
                <p>
                  <a href="{{ env('IOS_USER') }}" target="_blank">
                    <img src="{{asset('/Juno_clone/images/istore.png')}}" alt="images" width="160"/>
                  </a>
                </p>
              </div>
            </div>
          </div>
          </div><!--footer-main-one-->
        </div>
        <div class="footer-sub clearfix">
          <div class="hidden-lg text-center">
            <ul class="list-unstyled">
              <li><a href="/terms" target="_blank">Terms</a></li>
              <li><a href="/privacy" target="_blank">Privacy</a></li>
            </ul>
            <p>&copy; Goridey 2017 - All rights reserved</p>
            <ul class="list-unstyled social-icons">
              <li><a href="https://www.facebook.com/GoRidey1" target="_blank"><i class="fa fa-facebook"></i></a></li>
              <li><a href="https://twitter.com/Go_Ridey" target="_blank"><i class="fa fa-twitter"></i></a></li>
              <li><a href="https://www.youtube.com/channel/UCHnrBG1hHGtnpHeQEaRxfEA" target="_blank"><i class="fa fa-youtube"></i></a></li>
              <li><a href="https://plus.google.com/u/0/b/109808777039285499170/109808777039285499170" target="_blank"><i class="fa fa-google-plus"></i></a></li>
              <li><a href="https://www.linkedin.com/company/goridey" target="_blank"><i class="fa fa-linkedin"></i></a></li>
              <li><a href="https://www.instagram.com/goridey/" target="_blank"><i class="fa fa-instagram"></i></a></li>
            </ul>
          </div>
          <span class="hidden-xs hidden-sm hidden-md">
          <span>
            <ul class="list-unstyled">
              <li><a href="/terms" target="_blank">Terms</a></li>
              <li><a href="/privacy" target="_blank">Privacy</a></li>
              <li>&copy; Ridey Technologies Inc 2017 - All rights reserved</li>
            </ul>
          </span>
          <span class="pull-right">
            <ul class="list-unstyled">
              <li><a href="https://www.facebook.com/GoRidey1" target="_blank"><i class="fa fa-facebook"></i></a></li>
              <li><a href="https://twitter.com/Go_Ridey" target="_blank"><i class="fa fa-twitter"></i></a></li>
              <li><a href="https://www.youtube.com/channel/UCHnrBG1hHGtnpHeQEaRxfEA" target="_blank"><i class="fa fa-youtube"></i></a></li>
              <li><a href="https://plus.google.com/u/0/b/109808777039285499170/109808777039285499170" target="_blank"><i class="fa fa-google-plus"></i></a></li>
              <li><a href="https://www.linkedin.com/company/goridey" target="_blank"><i class="fa fa-linkedin"></i></a></li>
              <li><a href="https://www.instagram.com/goridey/" target="_blank"><i class="fa fa-instagram"></i></a></li>
            </ul>
          </span>
          </span>
        </div>
      </footer>
      <!--********************Footer*********************-->
      <a href="javascript:void(0);" id="scroll" title="Scroll to Top" style="display: none;">
        <i>  <svg style="width:24px;height:24px" viewBox="0 0 24 24">
          <path fill="#ffffff" d="M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z" />
        </svg></i>
      </a>
    </div>

  </body>
  <!--js-->
  <script src="{{asset('/Juno_clone/js/jquery.min.js')}}"></script>
  <script src="{{asset('/Juno_clone/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('/Juno_clone/js/modernizr-2.6.2.min.js')}}"></script>
  <script src="{{asset('/Juno_clone/js/wow.min.js')}}"></script>
  <script>
  new WOW().init();
  </script>
  <!--scrolltop & navbar-->
  <script type='text/javascript'>
  $(document).ready(function(){
    $(window).scroll(function(){
      if ($(this).scrollTop() > 100) {
        $('#scroll').fadeIn();
        $('.navbar-default').css("background-color" , "hsla(0,0%,100%,.95)");
        $('.navbar-default').css("padding-bottom" , "1em");
        $('.navbar-default').css("box-shadow", "rgba(0, 0, 0, 0.4) 0px 2px 1px 0px");
        $('.navbar-default').css("border-bottom", "1px solid rgb(221, 221, 221");
        $('.navbar-brand').css("color","#000");
        $('.navbar-nav>li>a').css("color","#fff");
        $('.navbar-nav>li>a').css("background","#4d732a");
        $('.welcome_outer .navbar-default .navbar-brand:hover').css("color","#000");
        $('.navbar-default .navbar-toggle .icon-bar').css("background-color","#000");
      } else {
        $('#scroll').fadeOut();
        $('.navbar-default').css("background-color", "transparent");
        $('.navbar-brand').css("color","#fff");
        $('.navbar-nav>li>a').css("color","#fff");
        $('.navbar-default').css("box-shadow", "none");
        $('.navbar-default').css("border-bottom", "none");
        $('.navbar-nav>li>a').css("background","rgba(255,255,255,.4)");
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
