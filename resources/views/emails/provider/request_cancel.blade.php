
<?php
    use App\Helpers\Helper;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <title>{{Helper::settings('site_name')}}</title>
    <style type="text/css">
.ReadMsgBody { width: 100%; background-color: #ffffff; }
.ExternalClass { width: 100%; background-color: #ffffff; }
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
html { width: 100%; }
body { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; margin: 0; padding: 0; }
table { border-spacing: 0; border-collapse: collapse; table-layout: fixed; margin: 0 auto; }
table table table { table-layout: auto; }
img { display: block !important; }
table td { border-collapse: collapse; }
.yshortcuts a { border-bottom: none !important; }
a { color: #21b6ae; text-decoration: none; }

 @media only screen and (max-width: 640px) {
body { width: auto !important; }
table[class="table600"] { width: 450px !important; }
table[class="table-inner"] { width: 90% !important; }
table[class="table3-3"] { width: 100% !important; text-align: center !important; }
}
 @media only screen and (max-width: 479px) {
body { width: auto !important; }
table[class="table600"] { width: 290px !important; }
table[class="table-inner"] { width: 82% !important; }
table[class="table3-3"] { width: 100% !important; text-align: center !important; }
}
</style>

</head>

<body>
    <!-- Layout-->
    <table data-thumb="noti-3.jpg" data-module="Layout-3" data-bgcolor="Background" width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#f1f1f1">
        <tr>
            <td data-bg="Background" align="center" background="{{$site_url}}/email/bg-3.jpg" style="background-size:cover; background-position:top;">
                <table class="table600" width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td height="60"></td>
                    </tr>
                    <tr>
                        <td align="center">
                            <table data-border-top-color="Border Top" style="border-top:3px solid #C62828; border-radius:4px;box-shadow: 0px 3px 0px #bdc3c7;" bgcolor="#FFFFFF" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <table width="550" align="center" class="table-inner" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td height="15"></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <!-- logo -->

                                                    <table class="table3-3" width="50" border="0" align="left" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td align="center" style="line-height:0px;">
                                                                <img mc:edit="logo" data-crop="false" style="display:block; line-height:0px; font-size:0px; border:0px;max-height:30px;" src="{{Helper::settings('mail_logo')}}" alt="logo" />
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <!-- end logo -->

                                                    <!--Space-->

                                                    <table width="1" height="15" border="0" cellpadding="0" cellspacing="0" align="left">
                                                        <tr>
                                                            <td height="15" style="font-size: 0;line-height: 0;border-collapse: collapse;">
                                                                <p style="padding-left: 24px;">&nbsp;</p>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <!--End Space-->

                                                    <!-- detail -->

                                                    <table align="right" class="table3-3" width="160" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <!-- <td data-link-style="text-decoration:none; color:#91c444;" data-link-color="Content Link" mc:edit="detail" data-color="Main Text" data-size="Main Text" align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:13px; color:#7f8c8d; line-height:30px;">
                                                                <span style="font-weight: bold; color:#91c444;">Order Number</span>
                                                                : 123456
                                                            </td> -->
                                                        </tr>
                                                    </table>

                                                    <!-- end detail -->
                                                </td>
                                            </tr>
                                            
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="25"></td>
                    </tr>
                    <tr>
                        <td align="center">
                            <table align="center" bgcolor="#FFFFFF" style="box-shadow: 0px 3px 0px #bdc3c7; border-radius:4px;" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <table align="center" class="table-inner" width="500" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td height="50"></td>
                                            </tr>
                                            <!-- title -->
                                            <tr>
                                                <td data-link-style="text-decoration:none; color:#91c444;" data-link-color="Content Link" mc:edit="title" data-color="Headline" data-size="Headline" align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:30px; color:#3b3b3b; font-weight: bold; ">Sorry for the inconvenience</td>
                                            </tr>
                                            <!-- end title -->

                                            <tr>
                                                <td align="center">
                                                    <table width="25" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td data-border-bottom-color="Main Color" height="20" style="border-bottom:2px solid #C62828;"></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="20"></td>
                                            </tr>

                                            <!-- content -->
                                            <tr>
                                                <td data-link-style="text-decoration:none; color:#91c444;" data-link-color="Content Link" mc:edit="content" data-color="Main Text" data-size="Main Text" align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:13px; color:#7f8c8d; line-height:30px;">
                                                    Hi, {{$email_data['username']}}<br/>
                                                    <p>
                                                     Your request is cancelled by {{$email_data['provider_name']}}. please try again later or search new people around you.
                                                    </p>
                                                   <!--  <b>Request Time : 05:25AM</b>
                                                    <br>
                                                    <b>Provider Name : John</b>
                                                    <br> -->
                                                </td>
                                            </tr>
                                            <!-- end content -->
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="30"></td>
                                </tr>

                                <!-- button -->
                                <tr>
                                    <td data-bgcolor="Content BG" align="center" bgcolor="#f5f5f5">
                                        <table align="center" class="table-inner" width="550" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td height="30"></td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <table data-bgcolor="Main Color" align="center" bgcolor="#C62828" border="0" cellspacing="0" cellpadding="0" style=" border-radius:30px; box-shadow: 0px 2px 0px #dedfdf;">
                                                        <tr>
                                                            <td mc:edit="button" height="55" align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:16px; color:#7f8c8d; line-height:30px; font-weight: bold;padding-left: 25px;padding-right: 25px;">
                                                                <a href="#" style="color:#ffffff;text-decoration:none;" data-color="Button Link">{{Helper::tr('find_new')}}</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="30"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- end button -->
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="25"></td>
                    </tr>
                    <tr>
                        <td>
                            <!-- left -->

                            <table bgcolor="#f5f5f5" style="box-shadow: 0px 3px 0px #bdc3c7; border-radius:4px;" class="table3-3" align="left" width="183" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td data-link-style="text-decoration:none; color:#3b3b3b;" data-link-color="Text Link" mc:edit="button-1" height="50" align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:14px; color:#3b3b3b; line-height:30px;padding-left: 25px;padding-right: 25px;">
                                        <a href="{{$site_url}}" target="_blank" style="color:#3b3b3b;text-decoration:none;" data-color="Text Link">{{Helper::tr('need_help')}}</a>
                                    </td>
                                </tr>
                            </table>

                            <!-- end left -->

                            <!--Space-->

                            <table width="1" height="25" border="0" cellpadding="0" cellspacing="0" align="left">
                                <tr>
                                    <td height="25" style="font-size: 0;line-height: 0;border-collapse: collapse;">
                                        <p style="padding-left: 24px;">&nbsp;</p>
                                    </td>
                                </tr>
                            </table>

                            <!--End Space-->

                            <!-- middle -->

                            <table bgcolor="#f5f5f5" style="box-shadow: 0px 3px 0px #bdc3c7; border-radius:4px;" class="table3-3" align="left" width="183" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td data-link-style="text-decoration:none; color:#3b3b3b;" data-link-color="Text Link" mc:edit="button-2" height="50" align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:14px; color:#3b3b3b; line-height:30px;padding-left: 25px;padding-right: 25px;">
                                        <a href="{{$site_url}}" target="_blank" style="color:#3b3b3b;text-decoration:none;" data-color="Text Link">{{Helper::tr('search_new')}}</a>
                                    </td>
                                </tr>
                            </table>

                            <!-- end middle -->

                            <!--Space-->

                            <table width="1" height="25" border="0" cellpadding="0" cellspacing="0" align="left">
                                <tr>
                                    <td height="25" style="font-size: 0;line-height: 0;border-collapse: collapse;">
                                        <p style="padding-left: 24px;">&nbsp;</p>
                                    </td>
                                </tr>
                            </table>

                            <!--End Space-->

                            <!-- right -->

                            <table bgcolor="#f5f5f5" style="box-shadow: 0px 3px 0px #bdc3c7; border-radius:4px;" class="table3-3" align="right" width="183" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td data-link-style="text-decoration:none; color:#3b3b3b;" data-link-color="Text Link" mc:edit="button-3" height="50" align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:14px; color:#3b3b3b; line-height:30px;padding-left: 25px;padding-right: 25px;">
                                        <a href="{{$site_url}}" target="_blank" style="color:#3b3b3b;text-decoration:none;" data-color="Text Link">{{Helper::tr('visit_website')}}</a>
                                    </td>
                                </tr>
                            </table>

                            <!-- end right -->
                        </td>
                    </tr>
                    <tr>
                        <td height="20"></td>
                    </tr>

                    <!-- copyright -->
                    <tr>
                        <td data-link-style="text-decoration:none; color:#3cb2d0;" data-link-color="Copyright Link" data-color="Copyright" data-size="Copyright" mc:edit="copyright" align="center" style="font-family: 'Open Sans', Arial, sans-serif; font-size:13px; color:#000000; line-height:30px;">
                            © 2016
                            <span style="color:#000; font-weight: bold;">{{Helper::settings('site_name')}}</span>
                            . All Rights Reserved.
                        </td>
                    </tr>
                    <!-- end copyright -->


                    <!-- option -->

                    <tr>
                        <td height="30"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!-- end Layout-3 -->
</body>
</html>
