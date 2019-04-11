@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content-header', 'Dashboard')

@section('breadcrumb')
    <li class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
@endsection

<style type="text/css">
  .center-card{
    	width: 30% !important;
	}
  .small-box .icon {
    top: 0px !important;
  }
  .info-box-icon {
  	height: 55px !important;
  }

  .info-box {
  		min-height: 40px !important;
  }
  .pt-6 {
  	padding-top: 6px !important;
  }
</style>

@section('content')

@include('notification.notify')
	<div class="row">

		<div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon pt-6 bg-red"><i class="fa fa-send"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">Total Requests </span>
					<span class="info-box-number">{{$total_requests}}</span>
				</div>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon pt-6 bg-yellow"><i class="fa fa-thumbs-up"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">Completed Req</span>
					<span class="info-box-number">{{$completed}}</span>
				</div>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon pt-6 bg-blue"><i class="fa fa-spinner"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">Ongoing Req</span>
					<span class="info-box-number">{{$ongoing}}</span>
				</div>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon pt-6 bg-green"><i class="fa fa-ban"></i></span>

				<div class="info-box-content">
					<span class="info-box-text">Cancelled Req</span>
					<span class="info-box-number">{{$cancelled}}</span>
				</div>
				<!-- /.info-box-content -->
			</div>
			<!-- /.info-box -->
        </div>
	</div>

	<div class="row">

		<!-- Total Amount -->

		<div class="col-lg-3 col-xs-6">

          	<div class="small-box bg-green">
            	<div class="inner">
              		<h3>{{get_currency_value($total ? $total : 0)}}</h3>
              		<p>{{tr('total')}}</p>
            	</div>

            	<div class="icon">
              		<i class="fa fa-shopping-bag"></i>
            	</div>

            	<!-- <a target="_blank" href="{{route('admin.users')}}" class="small-box-footer"> -->
              		
              		<!-- <i class="fa fa-arrow-circle-right"></i> -->
            	</a>
          	</div>

        </div>

		<!-- Total Moderators -->

        <div class="col-lg-3 col-xs-6">

          	<div class="small-box bg-blue">
            	<div class="inner">
              		<h3>{{get_currency_value($paypal_total ? $paypal_total : 0)}}</h3>
              		<p>Banking</p>
            	</div>

            	<div class="icon">
              		<i class="fa fa-paypal"></i>
            	</div>

            	<!-- <a target="_blank" href="#" class="small-box-footer"> -->
              		
              		<!-- <i class="fa fa-arrow-circle-right"></i> -->
            	</a>
          	</div>

        </div>

        <div class="col-lg-3 col-xs-6">

          	<div class="small-box bg-yellow">
            	<div class="inner">
              		<h3>{{get_currency_value($card_total ? $card_total : 0)}}</h3>
              		<p>{{tr('card')}}</p>
            	</div>

            	<div class="icon">
              		<i class="fa fa-credit-card-alt"></i>
            	</div>

            	<!-- <a target="_blank" href="#" class="small-box-footer"> -->
              		
              		<!-- <i class="fa fa-arrow-circle-right"></i> -->
            	</a>
          	</div>

        </div>

        <div class="col-lg-3 col-xs-6">

            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{get_currency_value($cod_total ? $cod_total : 0)}}</h3>
                    <p>{{tr('cod')}}</p>
                </div>

                <div class="icon">
                    <i class="fa fa-money"></i>
                </div>

                <!-- <a target="_blank" href="{{route('admin.user.payments')}}" class="small-box-footer"> -->
                    
                    <!-- <i class="fa fa-arrow-circle-right"></i> -->
                </a>
            </div>

        </div>


	</div>

    <div class="row">
        <div class="col-md-8">
            <div class="box">

                <div class="box-header with-border">

                    <h3 class="box-title">{{tr('daily_view_count')}}</h3>

                    <div class="box-tools pull-right">

                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>

                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>

                <!-- /.box-header -->

                <div class="box-body">
                    <div class="row">

                        <div class="col-md-12">
                            <p class="text-center">
                                <strong>{{tr('last_10_days')}}</strong>
                            </p>

                            <div class="chart">
                                <!-- Sales Chart Canvas -->
                                <canvas id="dailyChart" style="height: 300px;"></canvas>
                            </div>
                        </div>

                    </div>
                    <!-- /.row -->

                </div>
                <!-- ./box-body -->
            </div>
            <!-- /.box -->
        </div>

        <div class="col-md-4">
            <div class="box">

                <div class="box-header with-border">

                    <h3 class="box-title">{{tr('registered_users')}}</h3>

                    <div class="box-tools pull-right">

                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>

                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>

                <!-- /.box-header -->

                <div class="box-body">
                    <div class="row">

                        <div class="col-md-12">
                            <p class="text-center">
                                <strong></strong>
                            </p>

                            <div class="chart-responsive">
                                <canvas id="registerChart" height="200px"></canvas>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="box-footer no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li>
                            <a href="#">
                                <strong class="text-red">{{tr('total_web')}}</strong>
                                <span class="pull-right text-red">
                                    <i class="fa fa-angle-right"></i> {{$get_registers['web']}}
                                </span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <strong class="text-green">{{tr('total_android')}} </strong>
                                <span class="pull-right text-green">
                                    <i class="fa fa-angle-right"></i> {{$get_registers['android']}}
                                </span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <strong class="text-yellow">{{tr('total_ios')}}</strong>
                                <span class="pull-right text-yellow">
                                    <i class="fa fa-angle-right"></i> {{$get_registers['ios']}}
                                </span>
                            </a>
                        </li>
                  </ul>
                </div>
            </div>


        </div>

        <!-- /.col -->

    </div>

    <div class="row">

        @if(count($recent_users) > 0)

	        <div class="col-md-6">
	              <!-- USERS LIST -->
	            <div class="box box-danger">

	                <div class="box-header with-border">
	                    <h3 class="box-title">{{tr('latest_users')}}</h3>

	                    <div class="box-tools pull-right">
	                        <!-- <span class="label label-danger">8 New Members</span> -->
	                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                        </button>
	                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
	                        </button>
	                    </div>
	                </div>

	                <!-- /.box-header -->
	                <div class="box-body no-padding">
	                    <ul class="users-list clearfix">
	                        @foreach($recent_users as $user)

	                            <li>
	                                <img style="width:60px;height:60px" src="@if($user->picture) {{$user->picture}} @else {{asset('placeholder.png')}} @endif" alt="User Image">
	                                <a class="users-list-name" href="#">{{$user->name}}</a>
	                                <span class="users-list-date">{{$user->created_at->diffForHumans()}}</span>
	                            </li>

	                        @endforeach
	                    </ul>
	                  <!-- /.users-list -->
	                </div>
	                <!-- /.box-body -->

	                <div class="box-footer text-center">
	                    <a href="{{route('admin.users')}}" class="uppercase">{{tr('view_all')}}</a>
	                </div>

	                <!-- /.box-footer -->
	            </div>

	              <!--/.box -->
	        </div>

        @endif

        @if(count($provider_reviews) > 0)

            <div class="col-md-6">
                <div class="box box-success">

                    <div class="box-header with-border">
                        <h3 class="box-title">User Reviews</h3>

                        <div class="box-tools pull-right">

                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>

                            <button type="button" class="btn btn-box-tool" data-widget="remove">
                                <i class="fa fa-times"></i>
                            </button>
                      </div>

                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">

                        <ul class="products-list product-list-in-box">

                            @foreach($provider_reviews as $v => $data)

                                @if($v < 5)
                                    <li class="item">

                                        <div class="product-img">
                                            <img src="@if($data->user_picture) {{$data->user_picture}} @else {{asset('placeholder.png')}} @endif" alt="">
                                        </div>

                                        <div class="product-info">

                                            <a href="javascript:void(0)" class="product-title">

                                            	{{$data->user_first_name}} {{$data->user_last_name}}

                                                <span class="label label-warning pull-right">{{$data->rating}}</span>
                                            </a>
                                            <span class="product-description">
                                              {{substr($data->comment , 0 , 75)}}
                                            </span>
                                      </div>
                                    </li>

                                @endif
                            @endforeach
                            <!-- /.item -->
                        </ul>
                    </div>

                    <!-- /.box-body -->
                    <div class="box-footer text-center">
                        <a href="{{route('admin.user_reviews')}}" class="uppercase">{{tr('view_all')}}</a>
                    </div>
                    <!-- /.box-footer -->
                </div>
            </div>

        @endif

    </div>


@endsection


@section('scripts')

<script type="text/javascript">

//-------------
  //- PIE CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var pieChartCanvas = $("#registerChart").get(0).getContext("2d");
  var pieChart = new Chart(pieChartCanvas);
  var PieData = [
    {
      value: {{$get_registers['web']}},
      color: "#f56954",
      highlight: "#f56954",
      label: "Web"
    },
    {
      value: {{$get_registers['android']}},
      color: "#00a65a",
      highlight: "#00a65a",
      label: "Andorid"
    },
    {
      value: {{$get_registers['ios']}},
      color: "#f39c12",
      highlight: "#f39c12",
      label: "iOS"
    }
  ];
  var pieOptions = {
    //Boolean - Whether we should show a stroke on each segment
    segmentShowStroke: true,
    //String - The colour of each segment stroke
    segmentStrokeColor: "#fff",
    //Number - The width of each segment stroke
    segmentStrokeWidth: 1,
    //Number - The percentage of the chart that we cut out of the middle
    percentageInnerCutout: 50, // This is 0 for Pie charts
    //Number - Amount of animation steps
    animationSteps: 100,
    //String - Animation easing effect
    animationEasing: "easeOutBounce",
    //Boolean - Whether we animate the rotation of the Doughnut
    animateRotate: true,
    //Boolean - Whether we animate scaling the Doughnut from the centre
    animateScale: false,
    //Boolean - whether to make the chart responsive to window resizing
    responsive: true,
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: false,
    //String - A legend template
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
    //String - A tooltip template
    tooltipTemplate: "<%=value %> <%=label%> users"
  };
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  pieChart.Doughnut(PieData, pieOptions);
  //-----------------
  //- END PIE CHART -
  //-----------------

   //-----------------------
  //- MONTHLY SALES CHART -
  //-----------------------

  // Get context with jQuery - using jQuery's .get() method.
  var salesChartCanvas = $("#dailyChart").get(0).getContext("2d");
  // This will get the first returned node in the jQuery collection.
  var salesChart = new Chart(salesChartCanvas);

  var salesChartData = {
    labels: [<?php foreach($view['get'] as $date) { echo '"'.date('d M', strtotime($date->created_at)).'",';} ?>],
    datasets: [
      {
        label: "Electronics",
        fillColor: "rgb(210, 214, 222)",
        strokeColor: "rgb(210, 214, 222)",
        pointColor: "rgb(210, 214, 222)",
        pointStrokeColor: "#c1c7d1",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgb(220,220,220)",
        data: [<?php foreach($view['get'] as $count) { echo $count->count.',';} ?>]
      }
    ]
  };

  var salesChartOptions = {
    //Boolean - If we should show the scale at all
    showScale: true,
    //Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines: false,
    //String - Colour of the grid lines
    scaleGridLineColor: "rgba(0,0,0,.05)",
    //Number - Width of the grid lines
    scaleGridLineWidth: 1,
    //Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    //Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines: true,
    //Boolean - Whether the line is curved between points
    bezierCurve: true,
    //Number - Tension of the bezier curve between points
    bezierCurveTension: 0.3,
    //Boolean - Whether to show a dot for each point
    pointDot: false,
    //Number - Radius of each point dot in pixels
    pointDotRadius: 4,
    //Number - Pixel width of point dot stroke
    pointDotStrokeWidth: 1,
    //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius: 20,
    //Boolean - Whether to show a stroke for datasets
    datasetStroke: true,
    //Number - Pixel width of dataset stroke
    datasetStrokeWidth: 2,
    //Boolean - Whether to fill the dataset with a color
    datasetFill: true,
    //String - A legend template
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%=datasets[i].label%></li><%}%></ul>",
    //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio: true,
    //Boolean - whether to make the chart responsive to window resizing
    responsive: true
  };

  //Create the line chart
  salesChart.Line(salesChartData, salesChartOptions);

  //---------------------------
  //- END MONTHLY SALES CHART -
  //---------------------------
</script>

@endsection
