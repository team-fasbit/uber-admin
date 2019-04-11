<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{Setting::get('site_name')}} | @yield('title') </title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/Juno_clone/images/favicon-16x16.png')}}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{asset('admin-css/bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('admin-css/bootstrap/css/ionicons.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('admin-css/dist/css/AdminLTE.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('admin-css/plugins/iCheck/square/blue.css')}}">
    

    <!-- <link rel="shortcut icon" href="@if( Setting::get('site_icon')) {{ Setting::get('site_icon') }} @else {{asset('favicon.png')}} @endif"> -->

</head>

<body class="hold-transition login-page">

<style>
    
    </style>
    <div class="login_outer">
        <div class="login_outer_overlay"></div>
        <div class="inner">
             <div class="col-md-6 col-md-offset-3">

        @include('notification.notify')

    </div>

    <div class="login-box">



        @yield('content')

    </div>
    <!-- <div class="main-content">
            
            <p>Please note</p>
            <p>- To avoid vandalism, This DEMO Admin panel is NOT connected to the DEMO apps.</p>
            <p>- This is a replica of the Demo Admin panel. Setup exclusively for you to play around and check the Admin panel in action. <br>But any changes you make wont be reflected in the DEMO apps.</p>
           
        </div> -->
        </div>
    </div>
   

    <!-- jQuery 2.2.0 -->
    <script src="{{asset('admin-css/plugins/jQuery/jQuery-2.2.0.min.js')}}"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="{{asset('admin-css/bootstrap/js/bootstrap.min.js')}}"></script>
    <!-- iCheck -->
    <script src="{{asset('admin-css/plugins/iCheck/icheck.min.js')}}"></script>
    
    <script>
        $(function () {
            $('input').iCheck({
              checkboxClass: 'icheckbox_square-blue',
              radioClass: 'iradio_square-blue',
              increaseArea: '20%' // optional
            });

           
        });

    </script>


    @yield('bottom-scripts')

</body>

</html>
