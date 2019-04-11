<?php
use App\Helpers\Helper;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <title>Ridey</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <style type="text/css">
.ReadMsgBody { width: 100%; background-color: #ffffff; }
.ExternalClass { width: 100%; background-color: #ffffff; }
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
html { width: 100%; }
body { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; margin: 0; padding: 0;font-family: 'Open Sans', sans-serif !important; }
table { border-spacing: 0; border-collapse: collapse; table-layout: fixed; margin:0 auto; }
table table table { table-layout: auto; }
img { display: block !important; }
table td { border-collapse: collapse; }
.yshortcuts a { border-bottom: none !important; }
a { color: #ff646a; text-decoration: none; }
.textbutton a { color: #ffffff !important; }
.footer-link a { color: #7f8c8d !important; }


</style>
</head>

<body>
   <!--  <div style="background: #f5f5f5;padding: 2em 0 2em 1em;">
<img src="{{ asset('images/email_ridey.png') }}" style=" width: 150px;margin: auto;">
</div> -->
     <div id=""><img src="{{ $email_data['map_image'] }}"  height="280px" style="width:100%;"></div>
     <div style="height:109px;background-image: url('http://goridey.com/images/ridey_banner_org.png');"></div>
    <!-- header -->
    <table data-thumb="header.jpg" data-module="Header" data-bgcolor="Header" bgcolor="#f8f8f8" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr align="center" valign="top">
            <td>
                <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                  <!-- <tr>
                    <div id=""><img src="{{ $email_data['map_image'] }}" width="150px" height="150px"></div>
                  </tr> -->
                    <tr>
                        <td data-bgcolor="Alternate Color" width="208" align="center" valign="top" bgcolor="">
                            <table width="520" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td height="50"></td>
                                </tr>
                                <!-- Ride date -->
                                <tr>
                                    <td align="left" style="line-height:0px;">
                                        <h3 style="line-height: 26px;margin: 0;">{{ $email_data['requested_date']}}</h3>
                                        <P class="lead" style="color: #999;font-size: 15px;font-weight: bold;padding-top: 4px;">Car type {{ $email_data['car_type'] }}</p>
                                    </td>
                                </tr>
                                <!-- end logo -->

                                <tr>
                                    <td height="40"></td>
                                </tr>

                                <!-- Compane Name -->
                                <tr>
                                    <td data-link-style="text-decoration:none; color:#3b3b3b;font-weight:bold;" data-link-color="Address Link 1" mc:edit="Company Name" data-color="Company Title" style="font-size:16px; color:#3b3b3b; line-height:26px;padding-bottom:10px;overflow:hidden; ">
                                      <span style="width:10px;height:10px;background:green;display:inline-block;border-radius:50%;margin:0 10px 0 0;"></span>
                                      {{ $email_data['s_address'] }}
                                    </td>
                                </tr>
                                <!-- end Compane Name -->

                                <!-- Compane Name -->
                                @if($email_data['d_address'])
                                <tr>
                                    <td data-link-style="text-decoration:none; color:#3b3b3b;font-weight:bold;" data-link-color="Address Link 1" mc:edit="Company Name" data-color="Company Title" style=" font-size:16px; color:#3b3b3b; line-height:26px; ">
                                      <!-- <span style="width:2px;height: 26px;background-color:#444;display:inline-block;margin:-17px 10px 0 4px;position:absolute;"></span> -->
                                      <span style="width:15px;height:10px;background-color:#444;display:inline-block;margin: 0 10px 0 -2px;"></span>
                                      {{ $email_data['d_address'] }}</td>
                                </tr>
                                @endif
                                <!-- end Compane Name -->

                                <!-- Compane Name -->
                                <tr>
                                    <td data-link-style="text-decoration:none; color:#3b3b3b;font-weight:bold;" data-link-color="Address Link 1" mc:edit="Company Name" data-color="Company Title" style=" font-size:16px; color:#3b3b3b; line-height:26px; font-weight: bold;padding: 25px 0;">

                                      <div class="media" style="overflow: hidden;">
                                        <div class="media-left" style="vertical-align: top;display: table-cell;    padding-right: 10px;">
                                          <a href="#" style="">
                                            <img class="media-object" src="{{ $email_data['provider_picture'] }}" alt="..." style="width:60px;border-radius:50%;display: block;height: 60px;">
                                          </a>
                                        </div>
                                        <div class="media-body" style="overflow: hidden;vertical-align: top;display: table-cell;width:1000px;">

                                          <p>You rode with {{ $email_data['provider_name'] }}</p>
                                        </div>
                                      </div>
                                    </td>
                                </tr>
                                <!-- end Compane Name -->

                                <tr>
                                    <td height="5"></td>
                                </tr>

                                <tr>
                                    <td height="25"></td>
                                </tr>
                            </table>
                        </td>
                        <td width="392" align="center" valign="top">
                            <table width="342" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td height="50">
                                       <!--  <img src="{{asset('../images/email_ridey.png')}}" style="width: 85px;float: right;margin-top: -30px;margin-bottom: 14px;margin-right: -8em"/> -->
                                    </td>
                                </tr>

                                <!-- title -->
                                <tr>
                                    <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Header Link 2" mc:edit="title" data-color="Title" align="right" style=" font-size:35px; color:#3b3b3b; line-height:26px;">${{ $email_data['total']}}</td>
                                </tr>
                                <!-- end title -->

                                <tr>
                                    <td height="25"></td>
                                </tr>

                                <tr>
                                    <td height="15"></td>
                                </tr>


                                <tr>
                                    <td height="25"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- end header -->
   <!-- title -->
    <table data-thumb="title.jpg" data-module="Title" bgcolor="#f8f8f8" align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <table align="center" width="550" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center" style="border-bottom:3px solid #bcbcbc;">
                            <table align="center" width="550" border="0" cellspacing="0" cellpadding="0">
                               <!--  <tr>
                                    <td height="20"></td>
                                </tr>
                                <tr>
                                  <div style="padding: 15px 0;background-color: #f5f5f5;margin: 0em 0 2em;width: 100%;border-color: #f8f8f8;outline: none;font-weight:700;font-size: 18px;letter-spacing:.4px;text-align: center;">Receipt</div>
                                </tr> -->
                                <!-- header -->
                                <tr>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Title Link" mc:edit="title bar 1" width="263" align="left" valign="top" style=" font-size:13px; color:#3b3b3b; line-height:26px;font-size: 20px; text-transform:uppercase;"><b>{{ strtoupper($email_data['car_type']) }}</b> Receipt</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- end header -->
                                <tr>
                                    <td height="10"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!-- end title -->

    <!-- list -->
    <table data-thumb="list.jpg" data-module="List" bgcolor="#f8f8f8" align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" style="padding-bottom: 5em;">
                <table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" >
                  <tr>

                  </tr>
                    <tr>
                        <td align="center">
                            <table width="550" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td height="25"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Content Link" mc:edit="desctiption" width="263" align="left" valign="top" style="font-size:14px; color:#3b3b3b; line-height:26px;  font-weight: bold;">Base Fare</td>

                                                <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Content Link" mc:edit="value-3" width="87" align="right" valign="top" style="font-size:14px; color:#3b3b3b; line-height:26px;  font-weight: bold;">${{  $email_data['base_price'] }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr style="border-top: 1px solid #ddd;margin-top: 10px;display: inline-block;width: 100%;">
                                    <td height="15"></td>
                                </tr>

                                <tr>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Content Link" mc:edit="desctiption" width="263" align="left" valign="top" style="font-size:14px; color:#3b3b3b; line-height:26px;  font-weight: bold;">Minimum Fare</td>

                                                <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Content Link" mc:edit="value-3" width="87" align="right" valign="top" style="font-size:14px; color:#3b3b3b; line-height:26px;  font-weight: bold;">${{  $email_data['min_fare'] }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr style="border-top: 1px solid #ddd;margin-top: 10px;display: inline-block;width: 100%;">
                                    <td height="15"></td>
                                </tr>

                                <tr>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Content Link" mc:edit="desctiption" width="263" align="left" valign="top" style="font-size:14px; color:#3b3b3b; line-height:26px;  font-weight: bold;">Time</td>

                                                <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Content Link" mc:edit="value-3" width="87" align="right" valign="top" style="font-size:14px; color:#3b3b3b; line-height:26px;  font-weight: bold;">${{  $email_data['total_time_price'] }} / {{  $email_data['total_time'] }} mins</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr style="border-top: 1px solid #ddd;margin-top: 10px;display: inline-block;width: 100%;">
                                    <td height="15"></td>
                                </tr>

                                <tr>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Content Link" mc:edit="desctiption" width="263" align="left" valign="top" style="font-size:14px; color:#3b3b3b; line-height:26px;  font-weight: bold;">Distance</td>

                                                <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Content Link" mc:edit="value-3" width="87" align="right" valign="top" style="font-size:14px; color:#3b3b3b; line-height:26px;  font-weight: bold;">${{  $email_data['total_distance_price'] }} / {{  $email_data['distance_travel'] }} miles</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr style="border-top: 1px solid #ddd;margin-top: 10px;display: inline-block;width: 100%;">
                                    <td height="15"></td>
                                </tr>

                                 <tr>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Content Link" mc:edit="desctiption" width="263" align="left" valign="top" style="font-size:14px; color:#3b3b3b; line-height:26px;  font-weight: bold;">Booking Fee</td>

                                                <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Content Link" mc:edit="value-3" width="87" align="right" valign="top" style="font-size:14px; color:#3b3b3b; line-height:26px;  font-weight: bold;">${{  $email_data['booking_fee'] }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr style="border-top: 1px solid #ddd;margin-top: 10px;display: inline-block;width: 100%;">
                                     <td height="15"></td>
                                </tr>

                                <tr>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Content Link" mc:edit="desctiption" width="263" align="left" valign="top" style="font-size:14px; color:#3b3b3b; line-height:26px;  font-weight: bold;">Service Tax</td>

                                                <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Content Link" mc:edit="value-3" width="87" align="right" valign="top" style="font-size:14px; color:#3b3b3b; line-height:26px;  font-weight: bold;">${{  $email_data['tax_price'] }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr style="border-top: 1px solid #ddd;margin-top: 10px;display: inline-block;width: 100%;">
                                     <td height="15"></td>
                                </tr>

                                <tr>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Content Link" mc:edit="desctiption" width="263" align="left" valign="top" style=" font-size:14px; color:#3b3b3b; line-height:26px;  font-weight: bold;">TOTAL</td>

                                                <td data-link-style="text-decoration:none; color:#ff646a;" data-link-color="Content Link" mc:edit="value-3" width="87" align="right" valign="top" style=" font-size:14px; color:#3b3b3b; line-height:26px;  font-weight: bold;">${{  $email_data['total'] }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="15"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
   

</body>
</html>
