<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('send_push', 'ProviderApiController@sendPush');
header('Access-Control-Allow-Origin: *');
header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );

Route::get('/cron/locked_providers/release', 'ApplicationController@release_locked_providers_cron');
Route::get('/', function () {
        $domain = $_SERVER['SERVER_NAME']; 
    if($domain == "goridey.com") {
        return view('ridey_welcome');
    }
    return view('welcome');
});

Route::get('/privacy', function () {
    return view('privacy');
});

Route::get('/terms', function () {
    return view('terms');
});

Route::get('/get_version', 'ApplicationController@check_app_version');
Route::get('/test_ios_push', 'ApplicationController@test_ios_user');

Route::post('send_app_link', 'AdminController@send_app_link')->name('admin.send_app_link');

Route::get('/location_update_trip','HomeController@location_update_trip');

Route::group(['prefix' => 'userApi'], function(){

    Route::post('/register','UserApiController@register');
    Route::post('/applyReferral','UserApiController@apply_referral');
    

    Route::group(['prefix' => 'tron'], function(){

        Route::post('/wallet/balance', 'UserApiController@getTronWalletBalance');
        Route::post('/wallet/balance/add', 'UserApiController@addTronWalletBalance');

    });
    


    Route::post('/login','UserApiController@login');
	
    Route::post('/test_invoice','UserApiController@test_invoice');

	Route::get('/userDetails','UserApiController@user_details');

    Route::post('/logout','UserApiController@logout');

	Route::post('/updateProfile', 'UserApiController@update_profile');

	Route::post('/forgotpassword', 'UserApiController@forgot_password');

	Route::post('/changePassword', 'UserApiController@change_password');

	Route::get('/tokenRenew', 'UserApiController@token_renew');

    Route::post('/validate_promo', 'UserApiController@validate_promo');

    Route::post('/adsManagement', 'UserApiController@adsManagement');

    Route::get('/sendOtp', 'UserApiController@send_otp');
    // Add Card
    Route::post('/addcard', 'UserApiController@userAddCard');

    //Get Card
    Route::get('/getcards' , 'UserApiController@getCards');

    //Select Card
	Route::post('/selectcard', 'UserApiController@selectCard');

    //Delete Card
	Route::post('/deletecard', 'UserApiController@deleteCard');

    //Get Braintree Token
    Route::post('/getbraintreetoken' , 'UserApiController@getBraintreeToken');

    //Testing Payment
    Route::post('/testpayment', 'UserApiController@testPayment');

	// Service Types Handle

	Route::post('/serviceList', 'UserApiController@service_list');

	Route::post('/singleService', 'UserApiController@single_service');

	// Payment modes

    Route::get('/getPaymentModes' , 'UserApiController@get_payment_modes');

    // Cancellation Reasons

    Route::post('/cancellationReasons' , 'UserApiController@cancellation_reasons');

    // User Favourites

    Route::post('/userFavourites' , 'UserApiController@user_favourites');
	
    Route::post('/adduserFavourite' , 'UserApiController@add_user_favourite');
    
    Route::post('/deleteuserFavourite' , 'UserApiController@delete_user_favourite');

	// Request Handle

	Route::post('/guestProviderList', 'UserApiController@guest_provider_list');

    //Fare Calculator

    Route::post('/fare_calculator', 'UserApiController@fare_calculator');

  // Scheduled Request
	Route::post('/laterRequest', 'UserApiController@request_later');

	Route::post('/upcomingRequest', 'UserApiController@get_upcoming_request');

    Route::get('/cancel_later_request','UserApiController@cancel_later_request');

    // Hourly Package 

    Route::post('/hourly_package_fare','UserApiController@hourly_package_fare');

    Route::get('/airport_details', 'UserApiController@airport_details');

    Route::get('/location_details', 'UserApiController@location_details');

    Route::post('/airport_package_fare','UserApiController@airport_package_fare');

	// Automated request
	Route::post('/sendRequest', 'UserApiController@send_request');

	// Manual request
	Route::post('/manual_create_request', 'UserApiController@manual_create_request');


	Route::post('/cancelRequest', 'UserApiController@cancel_request');

	Route::post('/waitingRequestCancel' ,'UserApiController@waiting_request_cancel');

	Route::post('/requestStatusCheck', 'UserApiController@request_status_check');

	Route::post('/payment' , 'UserApiController@paynow');

	Route::post('/paybypaypal', 'UserApiController@paybypaypal');

	Route::post('/rateProvider', 'UserApiController@rate_provider');

	Route::post('/history' , 'UserApiController@history');

	Route::post('/singleRequest' , 'UserApiController@single_request');

    // messaging notification api
    Route::get('/message_notification', 'UserApiController@message_notification');



	// Cards

	Route::post('/getUserPaymentModes', 'UserApiController@get_user_payment_modes');

	Route::post('/PaymentModeUpdate', 'UserApiController@payment_mode_update');

    Route::post('/message/get', 'UserApiController@message_get');

});

Route::get('/serviceList' , 'HomeController@service_list');

Route::group(['prefix' => 'providerApi'], function(){

	Route::post('/register','ProviderApiController@register');

	Route::post('/login','ProviderApiController@login');

	Route::get('/userdetails','ProviderApiController@profile');

	Route::post('/updateProfile', 'ProviderApiController@update_profile');

	Route::post('/forgotpassword', 'ProviderApiController@forgot_password');

	Route::post('/changePassword', 'ProviderApiController@changePassword');

	Route::get('/tokenRenew', 'ProviderApiController@tokenRenew');

	Route::post('locationUpdate' , 'ProviderApiController@location_update');

	Route::get('checkAvailableStatus' , 'ProviderApiController@check_available');

	Route::post('availableUpdate' , 'ProviderApiController@available_update');

    Route::post('/adsManagement', 'ProviderApiController@adsManagement');
    
	Route::post('/serviceAccept', 'ProviderApiController@service_accept');

	Route::post('/serviceReject', 'ProviderApiController@service_reject');

	Route::post('/providerStarted', 'ProviderApiController@providerstarted');

	Route::post('/arrived', 'ProviderApiController@arrived');

	Route::post('/serviceStarted', 'ProviderApiController@servicestarted');

    Route::post('/serviceCompleted', 'ProviderApiController@servicecompleted');

	Route::post('/codPaidConfirmation', 'ProviderApiController@cod_paid_confirmation');

	Route::post('/rateUser', 'ProviderApiController@rate_user');

	Route::post('/cancelrequest', 'ProviderApiController@cancelrequest');

	Route::post('/history', 'ProviderApiController@history');

    Route::post('/earnings', 'ProviderApiController@earnings');

	Route::post('/singleRequest' , 'ProviderApiController@single_request');

	Route::post('/incomingRequest', 'ProviderApiController@get_incoming_request');

	Route::post('/requestStatusCheck', 'ProviderApiController@request_status_check');

    Route::post('/logout','ProviderApiController@logout');



    Route::post('/messsage/get', 'ProviderApiController@message_get');

    Route::get('/documents', 'ProviderApiController@documents');

    Route::post('/upload_documents', 'ProviderApiController@upload_documents');

    Route::post('/update_timezone', 'ProviderApiController@updateTimezone');

    Route::get('/delete_document', 'ProviderApiController@delete_document');

    // messaging notification api
    Route::get('/message_notification', 'ProviderApiController@message_notification');

    Route::post('/message/get', 'ProviderApiController@message_get');


});

Route::get('/assign_next_provider_cron' , 'ApplicationController@assign_next_provider_cron');

Route::get('/scheduled_requests_cron', 'ApplicationController@scheduled_requests_cron');


// Admin Routes
Route::group(['prefix' => 'admin'], function(){

    Route::get('login', 'Auth\AdminAuthController@showLoginForm')->name('admin.login');

    Route::post('login', 'Auth\AdminAuthController@login')->name('admin.login.post');

    Route::get('logout', 'Auth\AdminAuthController@logout')->name('admin.logout');

    // Registration Routes...

    Route::get('register', 'Auth\AdminAuthController@showRegistrationForm');

    Route::post('register', 'Auth\AdminAuthController@register');

    // Password Reset Routes...
    Route::get('password/reset/{token?}', 'Auth\AdminPasswordController@showResetForm');

    Route::any('password/email', 'Auth\AdminPasswordController@sendResetLinkEmail')->name('admin.password.email');


    //Route::any('password/reset', 'Auth\CorporatePasswordController@reset')->name('corporate.password.reset');

    Route::post('password/reset', 'Auth\AdminPasswordController@reset')->name('admin.password.reset')->name('admin.password.reset');

    Route::get('password/getCredentials/{email?}/{reset_for?}', 'Auth\AdminPasswordController@getCredentials')->name('admin.password.getCredentials');

    Route::get('/', 'AdminController@dashboard')->name('admin.dashboard');

    Route::get('/profile', 'AdminController@profile')->name('admin.profile');

  	Route::post('/profile/save', 'AdminController@profile_process')->name('admin.save.profile');

  	Route::post('/change/password', 'AdminController@change_password')->name('admin.change.password');

    // Map View
    Route::get('/mapview', 'AdminController@mapview')->name('admin.mapview');

    Route::get('/user/mapview', 'AdminController@usermapview')->name('admin.usermapview');

    Route::get('/provider/details/{id}', 'AdminController@provider_details')->name('admin.provider_details');

    Route::get('/user/details/{id}', 'AdminController@user_details')->name('admin.user_details');

    // Corporate

    Route::get('/corporates', 'AdminController@corporates')->name('admin.corporates');

    Route::get('/add/corporate', 'AdminController@add_corporate')->name('admin.add.corporate');

    Route::get('/edit/corporate/{id}', 'AdminController@edit_corporate')->name('admin.edit.corporate');

    Route::post('/add/corporate', 'AdminController@add_corporate_process')->name('admin.save.corporate');

    Route::get('/delete/corporate/{id}', 'AdminController@delete_corporate')->name('admin.delete.corporate');

    Route::get('/corporate/approve/{id}/{status}', 'AdminController@corporate_approve')->name('admin.corporate.approve');

    Route::get('/corporate/history/{id}', 'AdminController@corporate_history')->name('admin.corporate.history');


    // Call Center Managers

    Route::get('/managers', 'AdminController@managers')->name('admin.managers');

    Route::get('/add/manager', 'AdminController@add_manager')->name('admin.add.manager');

    Route::get('/edit/manager/{id}', 'AdminController@edit_manager')->name('admin.edit.manager');

    Route::post('/add/manager', 'AdminController@add_manager_process')->name('admin.save.manager');

    Route::get('/delete/manager/{id}', 'AdminController@delete_manager')->name('admin.delete.manager');

    Route::get('/manager/approve/{id}/{status}', 'AdminController@manager_approve')->name('admin.manager.approve');

    Route::get('/manager/history/{id}', 'AdminController@manager_history')->name('admin.manager.history');

    Route::get('/view/manager/{option}/{id}', 'AdminController@manager_details')->name('admin.view.manager');

    // users

    Route::get('/users', 'AdminController@users')->name('admin.users');

    Route::group(['prefix' => 'tron'], function(){
        
        Route::post('/users/balances', 'AdminController@getUsersTronBalances')->name('admin.users.tron.balances');
        Route::get('settings', 'AdminController@showTronSettings')->name('admin.show.tron');

        Route::post('tron_api_url_save', 'AdminController@saveTronApiurl')->name('admin.save_tron_api_url');
        Route::get('create_address', 'AdminController@createTronAddress')->name('admin.create_tron_address');
        Route::post('save_address', 'AdminController@saveTronAddress')->name('admin.save_tron_address');
        Route::post('make_default', 'AdminController@makeDefaultWallet')->name('admin.make_default');
        Route::get('check_balance', 'AdminController@getTronBalance')->name('admin.check_tron_balance');

    });



    

    Route::get('/add/user', 'AdminController@add_user')->name('admin.add.user');

    Route::get('/edit/user', 'AdminController@edit_user')->name('admin.edit.user');

    Route::post('/add/user', 'AdminController@add_user_process')->name('admin.save.user');

    Route::get('/delete/user', 'AdminController@delete_user')->name('admin.delete.user');

    Route::get('/view/user/{option}/{id}', 'AdminController@user_details')->name('admin.view.user');

    Route::get('user_history/{option}/{id}', 'AdminController@user_history')->name('admin.user.history');

    //sub-admin

    Route::get('/sub_admins', 'AdminController@sub_admins')->name('admin.sub_admins');

    Route::get('/add/sub_admin', 'AdminController@add_sub_admin')->name('admin.add.sub_admin');

    Route::get('/edit/sub_admin', 'AdminController@edit_sub_admin')->name('admin.edit.sub_admin');

    Route::post('/add/sub_admin', 'AdminController@add_sub_admin_process')->name('admin.save.sub_admin');

    Route::get('/delete/sub_admin', 'AdminController@delete_sub_admin')->name('admin.delete.sub_admin');

    Route::get('/view/sub_admin/{option}/{id}', 'AdminController@sub_admin_details')->name('admin.view.sub_admin');

    Route::get('user_history/{option}/{id}', 'AdminController@user_history')->name('admin.user.history');

    //requests

    Route::get('/request/view/{id}', 'AdminController@view_request')->name('admin.view.request');

    //Route::get('test', 'AdminController@hello')->name('admin.test');


    // Provider

    Route::get('/providers', 'AdminController@providers')->name('admin.providers');

    Route::get('/add/provider', 'AdminController@add_provider')->name('admin.add.provider');

    Route::get('/edit/provider/{id}', 'AdminController@edit_provider')->name('admin.edit.provider');

    Route::post('/add/provider', 'AdminController@add_provider_process')->name('admin.save.provider');

    Route::get('/delete/provider/{id}', 'AdminController@delete_provider')->name('admin.delete.provider');

    Route::get('/provider/approve/{id}/{status}', 'AdminController@provider_approve')->name('admin.provider.approve');

	Route::get('/provider/history/{id}', 'AdminController@provider_history')->name('admin.provider.history');

    Route::get('/view/corporate/{option}/{id}', 'AdminController@corporate_details')->name('admin.view.corporate');

    Route::get('/view/provider/{id}', 'AdminController@provider_view_details')->name('admin.provider.view');

	Route::get('/provider/documents', 'AdminController@provider_documents')->name('admin.provider.document');


    // Corporate

    Route::get('/corporates', 'AdminController@corporates')->name('admin.corporates');

    Route::get('/add/corporate', 'AdminController@add_corporate')->name('admin.add.corporate');

    Route::get('/edit/corporate/{id}', 'AdminController@edit_corporate')->name('admin.edit.corporate');

    Route::post('/add/corporate', 'AdminController@add_corporate_process')->name('admin.save.corporate');

    Route::get('/delete/corporate/{id}', 'AdminController@delete_corporate')->name('admin.delete.corporate');

    Route::get('/corporate/approve/{id}/{status}', 'AdminController@corporate_approve')->name('admin.corporate.approve');

    Route::get('/corporate/history/{id}', 'AdminController@corporate_history')->name('admin.corporate.history');

    Route::get('/view/corporate/{id}', 'AdminController@corporate_view_details')->name('admin.corporate.view');

    Route::get('/corporate/documents', 'AdminController@corporate_documents')->name('admin.corporate.document');

    // Request details
    Route::get('/requests', 'AdminController@requests')->name('admin.requests');

    //finding the near-by providers
    
    Route::any('/find_providers', 'AdminController@find_providers')->name('admin.find_providers');

    // manually assigning to the selected provider

    Route::any('/manual_create_request', 'AdminController@manual_create_request')->name('admin.manual_create_request');

    //cancel request
    Route::get('/cancel_request/{request_id}', 'AdminController@cancel_request')->name('admin.cancel_request');    

    // User Payment details
    Route::get('user/payments' , 'AdminController@user_payments')->name('admin.user.payments');

    // Service type
    Route::get('/service_types', 'AdminController@service_types')->name('admin.service.types');

    Route::get('/add_service_type', 'AdminController@add_service_type')->name('admin.add.service.type');

    Route::post('/add_service_type', 'AdminController@add_service_process')->name('admin.add.service.process');

    Route::get('/edit_service/{id}', 'AdminController@edit_service')->name('admin.edit.service');

    Route::get('/delete_service/{id}', 'AdminController@delete_service')->name('admin.delete.service');

    //Hourly package

    Route::get('/hourly_packages', 'AdminController@hourly_packages')->name('admin.hourly_package');

    Route::get('/add_hourly_package', 'AdminController@add_hourly_package')->name('admin.add.hourly_package');

    Route::post('/add_hourly_package', 'AdminController@add_hourly_package_process')->name('admin.hourly_package.process');

    Route::get('/edit_hourly_package/{id}', 'AdminController@edit_hourly_package')->name('admin.edit.hourly_package');

    Route::get('/delete_hourly_package/{id}', 'AdminController@delete_hourly_package')->name('admin.delete.hourly_package');

    // Promo Codes
    Route::get('/promo_codes', 'AdminController@promo_codes')->name('admin.promo_codes');
    
    Route::get('/add_promo_code', 'AdminController@add_promo_code')->name('admin.add.promo_code');
    
    Route::post('/add_promo_code_process', 'AdminController@add_promo_code_process')->name('admin.promo_code.process');
    
    Route::get('/edit_promo_code', 'AdminController@edit_promo_code')->name('admin.edit.promo_code');

    Route::get('/delete_promo_code/{id}', 'AdminController@delete_promo_code')->name('admin.delete.promo_code');
    //Airport package

    Route::get('/airport_details', 'AdminController@airport_details')->name('admin.airport_details');

    Route::get('/add_airport_detail', 'AdminController@add_airport_detail')->name('admin.airport_detail.add');

    Route::post('/add_airport_detail', 'AdminController@add_airport_detail_process')->name('admin.airport_detail.process');

    Route::get('/edit_airport_detail/{id}', 'AdminController@edit_airport_detail')->name('admin.edit.airport_detail');

    Route::get('/delete_airport_detail/{id}', 'AdminController@delete_airport_detail')->name('admin.delete.airport_detail');


    Route::get('/location_details', 'AdminController@location_details')->name('admin.location_details');

    Route::get('/add_location_detail', 'AdminController@add_location_detail')->name('admin.location_detail.add');

    Route::post('/add_location_detail', 'AdminController@add_location_detail_process')->name('admin.location_detail.process');

    Route::get('/edit_location_detail/{id}', 'AdminController@edit_location_detail')->name('admin.edit.location_detail');

    Route::get('/delete_location_detail/{id}', 'AdminController@delete_location_detail')->name('admin.delete.location_detail');


    Route::get('/airport_pricings', 'AdminController@airport_pricings')->name('admin.airport_pricings');

    Route::get('/add_airport_pricing', 'AdminController@add_airport_pricing')->name('admin.airport_pricing.add');

    Route::post('/add_airport_pricing', 'AdminController@add_airport_pricing_process')->name('admin.airport_pricing.process');

    Route::get('/edit_airport_pricing/{id}', 'AdminController@edit_airport_pricing')->name('admin.edit.airport_pricing');

    Route::get('/delete_airport_pricing/{id}', 'AdminController@delete_airport_pricing')->name('admin.delete.airport_pricing');


    //Documents

    Route::get('/documents', 'AdminController@documents')->name('admin.documents');

    Route::get('/add_document', 'AdminController@add_document')->name('admin.add_document');

    Route::post('/add_document_process', 'AdminController@add_document_process')->name('admin.add_document_process');

    Route::get('/document_edit/{id}', 'AdminController@document_edit')->name('admin.document_edit');

    Route::get('/delete_document/{id}', 'AdminController@delete_document')->name('admin.document_delete');

    //Currency

    Route::get('/currency', 'AdminController@currency')->name('admin.currency');

    Route::get('/add_currency', 'AdminController@add_currency')->name('admin.add_currency');

    Route::post('/add_currency_process', 'AdminController@add_currency_process')->name('admin.add_currency_process');

    Route::get('/currency_edit/{id}', 'AdminController@currency_edit')->name('admin.currency_edit');

    Route::get('/delete_currency/{id}', 'AdminController@delete_currency')->name('admin.currency_delete');

    //Cancellation Reasons

    Route::get('/cancellation_reasons', 'AdminController@cancellation_reasons')->name('admin.cancellation_reasons');

    Route::get('/add_cancellation_reason', 'AdminController@add_cancellation_reason')->name('admin.add_cancellation_reason');

    Route::post('/add_cancellation_reason_process', 'AdminController@add_cancellation_reason_process')->name('admin.add_cancellation_reason_process');

    Route::get('/cancellation_reason_edit/{id}', 'AdminController@cancellation_reason_edit')->name('admin.cancellation_reason_edit');

    Route::get('/delete_cancellation_reason/{id}', 'AdminController@delete_cancellation_reason')->name('admin.cancellation_reason_delete');



    //Advertisement

    Route::get('/Ads', 'AdminController@ads')->name('admin.ads');

    Route::get('/add_ads', 'AdminController@add_ads')->name('admin.add_ads');

    Route::post('/add_ads_process', 'AdminController@add_ads_process')->name('admin.add_ads_process');

    Route::get('/ads_edit/{id}', 'AdminController@ads_edit')->name('admin.ads_edit');

    Route::get('/delete_ads/{id}', 'AdminController@delete_ads')->name('admin.ads_delete');


    //Reviews & Ratings

    Route::get('/user_reviews', 'AdminController@user_reviews')->name('admin.user_reviews');

    Route::get('/provider_reviews', 'AdminController@provider_reviews')->name('admin.provider_reviews');

    Route::get('/provider_review_delete/{id}', 'AdminController@delete_provider_reviews')->name('admin.provider_review_delete');

    Route::get('/user_review_delete/{id}', 'AdminController@delete_user_reviews')->name('admin.user_review_delete');

    //payments
    Route::get('/payment', 'AdminController@payment')->name('admin.payments');

    // Settings

    Route::get('/settings', 'AdminController@settings')->name('admin.settings');

    Route::get('payment/settings' , 'AdminController@payment_settings')->name('admin.payment.settings');

    Route::post('settings' , 'AdminController@settings_process')->name('admin.save.settings');
    
    Route::get('push_notifications' , 'AdminController@push_notifications')->name('admin.push_notifications');

    Route::post('mass_push_notification_send' , 'AdminController@mass_push_notification_send')->name('admin.mass_push_notification_send');



});


//(Call Center) Manager routes
Route::group(['prefix' => 'manager'], function(){

    //login
    Route::get('login', 'Auth\ManagerAuthController@showLoginForm')->name('manager.login');

    Route::post('login', 'Auth\ManagerAuthController@login')->name('manager.login.post');

    //logout
    Route::get('logout', 'Auth\ManagerAuthController@logout')->name('manager.logout');

    // Password Reset Routes...
    Route::get('password/reset/{token?}', 'Auth\ManagerPasswordController@showResetForm');

    Route::post('password/email', 'Auth\ManagerPasswordController@sendResetLinkEmail');

    Route::post('password/reset', 'Auth\ManagerPasswordController@reset');

    //dashboard
    Route::get('/', 'ManagerController@dashboard')->name('manager.dashboard');

    //manager profile
    Route::get('/profile', 'ManagerController@profile')->name('manager.profile');

    Route::post('/profile/save', 'ManagerController@profile_process')->name('manager.save.profile');

    //change password
    Route::post('/change/password', 'ManagerController@change_password')->name('manager.change.password');

    //manager
    Route::get('/create_request', 'ManagerController@create_request_form')->name('manager.create_request');

    Route::post('/create_request', 'ManagerController@create_request')->name('manager.create_request.post');


    //hourly-package

    Route::post('/hourly_package_fare', 'ManagerController@hourly_package_fare')->name('manager.hourly_package_fare');


    //airport-package

    Route::get('/airport_details', 'ManagerController@airport_details')->name('manager.airport_details');

    Route::get('/location_details', 'ManagerController@location_details')->name('manager.location_details');

    Route::post('/airport_package_fare','ManagerController@airport_package_fare')->name('manager.airport_package_fare');



    //fare-estimate

    Route::any('/fare_estimate', 'ManagerController@fare_calculator')->name('manager.fare_calculator');
 
    // Request details

    Route::get('/requests', 'ManagerController@requests')->name('manager.requests');

    Route::get('/request/view/{id}', 'ManagerController@view_request')->name('manager.view.request');

    //finding the near-by providers

    Route::any('/find_providers', 'ManagerController@find_providers')->name('manager.find_providers');

    // manually assigning to the selected provider

    Route::any('/manual_create_request', 'ManagerController@manual_create_request')->name('manager.manual_create_request');

    //request_later

    Route::post('/request_later', 'ManagerController@request_later')->name('manager.request_later');



    //cancel_request 

    Route::any('/cancel_request/{request_id}', 'ManagerController@cancel_request')->name('manager.cancel_request');

    Route::any('/map', 'ManagerController@map')->name('manager.map');

});

//provider routes
Route::group(['prefix' => 'provider'], function(){
    // Password Reset Routes...
    Route::get('password/reset/{token?}', 'Auth\ProviderPasswordController@showResetForm');

    Route::post('password/email', 'Auth\ProviderPasswordController@sendResetLinkEmail');

    Route::post('password/reset', 'Auth\ProviderPasswordController@reset');
});

//user routes
Route::group(['prefix' => 'user'], function(){
    // Password Reset Routes...
    Route::get('password/reset/{token?}', 'Auth\UserPasswordController@showResetForm');

    Route::post('password/email', 'Auth\UserPasswordController@sendResetLinkEmail');

    Route::post('password/reset', 'Auth\UserPasswordController@reset');
});

// Corporate Routes
Route::group(['prefix' => 'corporate'], function(){

    Route::get('login', 'Auth\CorporateAuthController@showLoginForm')->name('corporate.login');

    Route::post('login', 'Auth\CorporateAuthController@login')->name('corporate.login.post');

    Route::get('logout', 'Auth\CorporateAuthController@logout')->name('corporate.logout');

    // Registration Routes...

    Route::get('register', 'Auth\CorporateAuthController@showRegistrationForm');

    Route::post('register', 'Auth\CorporateAuthController@register');

    // Password Reset Routes...
    Route::get('password/reset/{token?}', 'Auth\CorporatePasswordController@showResetForm');

    Route::post('password/email', 'Auth\CorporatePasswordController@sendResetLinkEmail');

    Route::post('password/reset', 'Auth\CorporatePasswordController@reset');

    Route::get('/', 'CorporateController@dashboard')->name('corporate.dashboard');

    Route::get('/profile', 'CorporateController@profile')->name('corporate.profile');

    Route::post('/profile/save', 'CorporateController@profile_process')->name('corporate.save.profile');

    Route::post('/change/password', 'CorporateController@change_password')->name('corporate.change.password');

    // Map View
    Route::get('/mapview', 'CorporateController@mapview')->name('corporate.mapview');

    Route::get('/user/mapview', 'CorporateController@usermapview')->name('corporate.usermapview');

    Route::get('/provider/details/{id}', 'CorporateController@provider_details')->name('corporate.provider_details');

    Route::get('/user/details/{id}', 'CorporateController@user_details')->name('corporate.user_details');

    Route::get('/request/view/{id}', 'CorporateController@view_request')->name('corporate.view.request');

  

    // Provider

    Route::get('/providers', 'CorporateController@providers')->name('corporate.providers');

    Route::get('/add/provider', 'CorporateController@add_provider')->name('corporate.add.provider');

    Route::get('/edit/provider/{id}', 'CorporateController@edit_provider')->name('corporate.edit.provider');

    Route::post('/add/provider', 'CorporateController@add_provider_process')->name('corporate.save.provider');

    Route::get('/delete/provider/{id}', 'CorporateController@delete_provider')->name('corporate.delete.provider');

    Route::get('/provider/approve/{id}/{status}', 'CorporateController@provider_approve')->name('corporate.provider.approve');

        Route::get('/provider/history/{id}', 'CorporateController@provider_history')->name('corporate.provider.history');

    Route::get('/view/provider/{id}', 'CorporateController@provider_view_details')->name('corporate.provider.view');

        Route::get('/provider/documents', 'CorporateController@provider_documents')->name('corporate.provider.document');

    // Request details

    Route::get('/requests', 'CorporateController@requests')->name('corporate.requests');


    // User Payment details
    Route::get('user/payments' , 'CorporateController@user_payments')->name('corporate.user.payments');



    //Reviews & Ratings

    Route::get('/user_reviews', 'CorporateController@user_reviews')->name('corporate.user_reviews');

    Route::get('/provider_reviews', 'CorporateController@provider_reviews')->name('corporate.provider_reviews');

    Route::get('/provider_review_delete/{id}', 'CorporateController@delete_provider_reviews')->name('corporate.provider_review_delete');

    Route::get('/user_review_delete/{id}', 'CorporateController@delete_user_reviews')->name('corporate.user_review_delete');

    //payments
    Route::get('/payment', 'CorporateController@payment')->name('corporate.payments');

    // Settings

    Route::get('/settings', 'CorporateController@settings')->name('corporate.settings');

    Route::get('payment/settings' , 'CorporateController@payment_settings')->name('corporate.payment.settings');

    Route::post('settings' , 'CorporateController@settings_process')->name('corporate.save.settings');



});
