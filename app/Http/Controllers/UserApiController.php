<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Helper;

use Log;

use Hash;

use Validator;

use File;

use DB;

use App\User;

use App\ProviderService;

use App\Requests;

use App\Admin;

use App\RequestsMeta;

use App\HourlyPackage;

use App\AirportDetail;

use App\AirportPrice;

use App\LocationDetail;

use App\ServiceType;

use App\Provider;

use App\Advertisement;

use App\Settings;

use App\RequestPayment;

use App\UserRating;

use App\ProviderRating;

use App\Cards;

use App\CancellationReasons;

use App\ChatMessage;

use App\Jobs\NormalPushNotification;

use App\Jobs\sendPushNotification;

use App\PromoCode;

use App\UserFavourite;

use App\Debt;

//Braintree Classes
use Braintree_Transaction;
use Braintree_Customer;
use Braintree_WebhookNotification;
use Braintree_Subscription;
use Braintree_CreditCard;
use Braintree_PaymentMethod;
use Braintree_ClientToken;

use App\Helpers\Tron;


if (!defined('USER')) define('USER',1);
if (!defined('PROVIDER')) define('PROVIDER',1);

if (!defined('NORMAL_REQUEST')) define('NORMAL_REQUEST',1);
if (!defined('HOURLY_PACKAGE')) define('HOURLY_PACKAGE',2);
if (!defined('AIRPORT_PACKAGE')) define('AIRPORT_PACKAGE',3);


if (!defined('NONE')) define('NONE', 0);

if (!defined('DEFAULT_FALSE')) define('DEFAULT_FALSE', 0);
if (!defined('DEFAULT_TRUE')) define('DEFAULT_TRUE', 1);

// Payment Constants
if (!defined('COD')) define('COD',   'cod');
if (!defined('PAYPAL')) define('PAYPAL', 'paypal');
if (!defined('CARD')) define('CARD',  'card');
if (!defined('WALLETBAY')) define('WALLETBAY',  'walletbay');


if (!defined('REQUEST_NEW')) define('REQUEST_NEW',        0);
if (!defined('REQUEST_WAITING')) define('REQUEST_WAITING',      1);
if (!defined('REQUEST_INPROGRESS')) define('REQUEST_INPROGRESS',    2);
if (!defined('REQUEST_COMPLETE_PENDING')) define('REQUEST_COMPLETE_PENDING',  3);
if (!defined('REQUEST_RATING')) define('REQUEST_RATING',      4);
if (!defined('REQUEST_COMPLETED')) define('REQUEST_COMPLETED',      5);
if (!defined('REQUEST_CANCELLED')) define('REQUEST_CANCELLED',      6);
if (!defined('REQUEST_NO_PROVIDER_AVAILABLE')) define('REQUEST_NO_PROVIDER_AVAILABLE',7);
if (!defined('REQUEST_TIME_EXCEED_CANCELLED')) define('REQUEST_TIME_EXCEED_CANCELLED',20);
if (!defined('WAITING_FOR_PROVIDER_CONFRIMATION_COD')) define('WAITING_FOR_PROVIDER_CONFRIMATION_COD',  8);


// Only when manual request
if (!defined('REQUEST_REJECTED_BY_PROVIDER')) define('REQUEST_REJECTED_BY_PROVIDER', 9);

if (!defined('PROVIDER_NOT_AVAILABLE')) define('PROVIDER_NOT_AVAILABLE', 0);
if (!defined('PROVIDER_AVAILABLE')) define('PROVIDER_AVAILABLE', 1);

if (!defined('PROVIDER_NONE')) define('PROVIDER_NONE', 0);
if (!defined('PROVIDER_ACCEPTED')) define('PROVIDER_ACCEPTED', 1);
if (!defined('PROVIDER_STARTED')) define('PROVIDER_STARTED', 2);
if (!defined('PROVIDER_ARRIVED')) define('PROVIDER_ARRIVED', 3);
if (!defined('PROVIDER_SERVICE_STARTED')) define('PROVIDER_SERVICE_STARTED', 4);
if (!defined('PROVIDER_SERVICE_COMPLETED')) define('PROVIDER_SERVICE_COMPLETED', 5);
if (!defined('PROVIDER_RATED')) define('PROVIDER_RATED', 6);

if (!defined('REQUEST_META_NONE')) define('REQUEST_META_NONE',   0);
if (!defined('REQUEST_META_OFFERED')) define('REQUEST_META_OFFERED',   1);
if (!defined('REQUEST_META_TIMEDOUT')) define('REQUEST_META_TIMEDOUT', 2);
if (!defined('REQUEST_META_DECLINED')) define('REQUEST_META_DECLINED', 3);

if (!defined('RATINGS')) define('RATINGS', '0,1,2,3,4,5');

if (!defined('DEVICE_ANDROID')) define('DEVICE_ANDROID', 'android');
if (!defined('DEVICE_IOS')) define('DEVICE_IOS', 'ios');
if (!defined('DEVICE_WEB')) define('DEVICE_WEB', 'web');

if (!defined('WAITING_TO_RESPOND')) define('WAITING_TO_RESPOND', 1);
if (!defined('WAITING_TO_RESPOND_NORMAL')) define('WAITING_TO_RESPOND_NORMAL',0);

if (!defined('LIMITED_OFFER')) define('LIMITED_OFFER',0);
if (!defined('GLOBAL_OFFER')) define('GLOBAL_OFFER',1);


class UserApiController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('UserApiVal' , array('except' => ['register' ,'apply_referral', 'login' , 'send_otp' , 'forgot_password']));

    }
    

    /** add tron wallet balance 
     * now payment gateway is not done so directly add
    */
    public function addTronWalletBalance(Request $request)
    {
        $usdAmount = $request->usd_amount;
        $marketPrice = Tron::marketPrice();

        $tronAmount = ($usdAmount / $marketPrice) * pow(10, 6); 
        //for now round it to integer
        $tronAmount = intval($tronAmount);
        $adminBalance = Tron::getAdminBalance();
        
        /** check admin balance is insufficient or not*/
        if($usdAmount < 0 || $adminBalance <= $tronAmount) {
            return response()->json([
                'success' => false, 
                'error' => 'insufficient_admin_balance', 'error_code' => 9901, 'error_messages' => 'Insufficient admin balance'
            ], 200);
        }


        /** fetch user default card and check before transaction*/
        $user = User::find($request->id);
        $userCard = User::where('users.id' , $request->id)
            ->leftJoin('cards' , 'users.id','=','cards.user_id')
            ->where('cards.id' , $user->default_card)
            ->where('cards.is_default' , DEFAULT_TRUE)
            ->first();

        /** if user card exists then make payment */
        if(!$userCard) {
            return response()->json([
                'success' => false, 
                'error' => 'no_default_card', 'error_code' => 9901, 'error_messages' => 'No default card exists. Add card please.'
            ], 200);
        }

        /** execution control reaches here means user has default card */
        $transaction = Helper::createTransaction($userCard->customer_id, uniqid($user->id.'_'), $usdAmount);
        
        /** check if transaction fails then send error response */
        if($transaction == '0') {
            $response_array = array('success' => false, 'error' => Helper::get_error_message(158) , 'error_code' => 158);
            return response()->json($response_array , 200);

        }

        /** execution control reaches here means transaction successful */
        /** getting admin account info eg address and privatekey */
        $adminAccount = Tron::getAdminInfo();
        /** getting user wallet info */
        $userAccount = Tron::getUserWallet($request->id);

        $transferRes = Tron::transfer(
            $adminAccount['address_hex'], //admin hex address
            $adminAccount['private_key'] , //admin private key 
            $userAccount->address_hex, //user hex address
            $tronAmount //usd equvalance tron amount
        );
        \Log::info('$transaction');
        \Log::info($transaction);
        
        if($transferRes['success']) {
            
            $marketPrice = Tron::marketPrice();
            $balance = Tron::getUserBalance($request->id);
            return response()->json([
                'success' => true, 
                'balance' => $balance,
                'market_price' => $marketPrice,
                'usd_equivalent' => number_format(round($balance * $marketPrice, 2), 2, '.', ''),
                'transaction_data' => $transferRes['data']
            ], 200);

        } else {

            return response()->json([
                'success' => false, 
                'error' => 'something_happened', 'error_code' => 9901, 'error_messages' => 'Failed to add to wallet'
            ], 200);            

        }

    }



    /**
     * get tron wallet balance
     */
    public function getTronWalletBalance(Request $request)
    {   
        $marketPrice = Tron::marketPrice();
        $wallet = Tron::getUserWallet($request->id);
        $balance = Tron::getUserBalance($request->id);
        return response()->json([
            'success' => true, 
            'wallet' => $wallet,
            'balance' => $balance / pow(10, 6),
            'market_price' => number_format($marketPrice, 6, '.', ''),
            'usd_equivalent' => number_format(round($balance * $marketPrice, 2), 2, '.', ''),
        ], 200);
    }




    /** 
     * update address and add stop api
     */
    public function updateAddress(Request $request)
    {
        

        $validator = Validator::make(
                $request->all(),
                array(
                    'request_id' => 'required',
                    'change_type' => 'required',
                    'address' => 'required',
                    'latitude' => 'required',
                    'longitude' => 'required',

                ));

        if ($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        } else {

            $requests = Requests::find($request->request_id);

            $request_start_time = $requests->start_time;
            $current_time = date("Y-m-d H:i:s");

            $difference = Helper::time_diff($request_start_time,$current_time);

            $update_time_setting = Settings::where('key','max_update_time')->first();
            $max_update_time = $update_time_setting->value;

            if($difference->i > $max_update_time and $requests->status >= 4){
                $response_array = array('success' => false , 'error' => "Sorry! You cannot change address now" ,'error_code' => 279);

            }else{

                // Additional stop address change
                if($request->change_type == 0 ){
                    $requests->adstop_address = $request->address;
                    $requests->adstop_latitude = $request->latitude;
                    $requests->adstop_longitude = $request->longitude;
                    $requests->is_adstop = 1;
                    $requests->save();

                    $response_array = array('success' => true,
                                        'change_type'=>$request->change_type,
                                        'adstop_address'=>$requests->adstop_address,
                                        'adstop_latitude' =>$requests->adstop_latitude,
                                        'adstop_longitude' =>$requests->adstop_longitude,
                                        'is_adstop' => $requests->is_adstop,
                                        'is_address_changed' =>$requests->is_address_changed
                                    );


                }elseif($request->change_type == 1 && $requests->is_address_changed == 0){
                    $requests->d_address = $request->address;
                    $requests->d_latitude = $request->latitude;
                    $requests->d_longitude = $request->longitude;
                    $requests->is_address_changed = 1;
                    $requests->save();

                     $response_array = array('success' => true,
                                        'change_type'=>$request->change_type,
                                        'd_address'=>$requests->d_address,
                                        'd_latitude' =>$requests->d_latitude,
                                        'd_longitude' =>$requests->d_longitude,
                                        'is_adstop' => $requests->is_adstop,
                                        'is_address_changed' =>$requests->is_address_changed
                                    );

                }
                else{
                    $response_array = array('success' => false , 'error' => "Sorry! You cannot change address now" ,'error_code' => 279);

                }

                

            }        


        }

        $response = response()->json($response_array , 200);
        return $response;

    }














    public function register(Request $request)
    {
        $response_array = array();
        $operation = false;
        $new_user = DEFAULT_TRUE;

        // validate basic field
        $device_type = $request->device_type;
        if($device_type == DEVICE_WEB) {
            $basicValidator = Validator::make(
            $request->all(),
            array(
                // 'device_token' => 'required',
                'device_type' => 'required|in:'.DEVICE_ANDROID.','.DEVICE_IOS.','.DEVICE_WEB,
                'login_by' => 'required|in:manual,facebook,google',
                )
            );
        }
        else 
        {
            $basicValidator = Validator::make(
            $request->all(),
            array(
                'device_token' => 'required',
                'device_type' => 'required|in:'.DEVICE_ANDROID.','.DEVICE_IOS.','.DEVICE_WEB,
                'login_by' => 'required|in:manual,facebook,google',
                )
            );
        }

        if($basicValidator->fails()) {

            $error_messages = implode(',', $basicValidator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages'=> $error_messages);
            Log::info('Registration basic validation failed');

        } else {

            $login_by = $request->login_by;
            $allowedSocialLogin = array('facebook','google');

            // check login-by

            if(in_array($login_by,$allowedSocialLogin)){

                // validate social registration fields

                $socialValidator = Validator::make(
                            $request->all(),
                            array(
                                'social_unique_id' => 'required',
                                'first_name' => 'required|max:255',
                                'last_name' => 'max:255',
                                'email' => 'required|email|max:255',
                                'mobile' => 'digits_between:6,13',
                                'picture' => 'mimes:jpeg,jpg,bmp,png',
                                'gender' => 'in:male,female,others',
                            )
                        );

                if($socialValidator->fails()) {

                    $error_messages = implode(',', $socialValidator->messages()->all());
                    $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages'=> $error_messages);

                    Log::info('Registration social validation failed');

                }else {

                    $check_social_user = User::where('email' , $request->email)->first();

                    if($check_social_user) {
                        $new_user = DEFAULT_FALSE;
                    }

                    Log::info('Registration passed social validation');
                    $operation = true;
                }

            } else {

                // Validate manual registration fields

                $manualValidator = Validator::make(
                    $request->all(),
                    array(
                        'first_name' => 'required|max:255',
                        'last_name' => 'required|max:255',
                        'email' => 'required|email|max:255',
                        'mobile' => 'required|digits_between:6,13',
                        'password' => 'required|min:6',
                        'picture' => 'mimes:jpeg,jpg,bmp,png',
                    )
                );

                // validate email existence

                $emailValidator = Validator::make(
                    $request->all(),
                    array(
                        'email' => 'unique:users,email',
                    )
                );

                if($manualValidator->fails()) {

                    $error_messages = implode(',', $manualValidator->messages()->all());
                    $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages'=> $error_messages);
                    Log::info('Registration manual validation failed');

                } elseif($emailValidator->fails()) {

                    $error_messages = implode(',', $emailValidator->messages()->all());
                    $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages'=> $error_messages);
                    Log::info('Registration manual email validation failed');

                } else {
                    Log::info('Registration passed manual validation');
                    $operation = true;
                }

            }

            if($operation) {

                // Creating the user
                if($new_user) {
                    $user = new User;
                    // Settings table - COD Check is enabled
                    if(Settings::where('key' , COD)->where('value' , DEFAULT_TRUE)->first()) {
                        // Save the default payment method
                        $user->payment_mode = COD;
                    }

                } else {
                    $user = $check_social_user;
                }

                if($request->has('first_name')) {
                    $user->first_name = $request->first_name;
                }

                if($request->has('last_name')) {
                    $user->last_name = $request->last_name;
                }

                if($request->has('timezone')){
                    $user->timezone = $request->timezone;
                }

                if($request->has('currency_code')){
                    $user->currency_code = $request->currency_code;
                }

                if($request->has('country')){
                    $user->country = $request->country;
                }

                if($request->has('email')) {
                    $user->email = $request->email;
                }

                if($request->has('mobile')) {
                    $user->mobile = $request->mobile;
                }

                if($request->has('timezone')) {
                    $user->timezone = $request->timezone;
                } else {
                    $user->timezone = 'UTC';
                }

                if($request->has('password'))
                    $user->password = Hash::make($request->password);

                $user->gender = $request->has('gender') ? $request->gender : "male";

                $user->token = Helper::generate_token();
                $user->token_expiry = Helper::generate_token_expiry();

                $check_device_exist = User::where('device_token', $request->device_token)->first();

                if($check_device_exist){
                    $check_device_exist->device_token = "";
                    $check_device_exist->save();
                }

                $user->device_token = $request->has('device_token') ? $request->device_token : "";
                $user->device_type = $request->has('device_type') ? $request->device_type : "";
                $user->login_by = $request->has('login_by') ? $request->login_by : "manual";
                $user->social_unique_id = $request->has('social_unique_id') ? $request->social_unique_id : '';

                // Upload picture
                if($request->hasFile('picture')) {
                    $user->picture = Helper::upload_picture($request->file('picture'));
                }

                $user->is_activated = 1;
                $user->is_approved = 1;

                $user->save();
                //$user->password = $request->password;
                $payment_mode_status = $user->payment_mode ? $user->payment_mode : 0;

                //Referral Code
                $first = $user->first_name;
                $ref_name = strtolower(substr(str_replace(' ','',$first),0,3));
                $ref_rand = rand(100,999);
                $referral_code = $ref_name.$ref_rand;

                $user->referral_code = $referral_code;
                $user->save();

                // Send welcome email to the new user:
                if($new_user) {
                    $subject = Helper::tr('user_welcome_title');
                    $email_data = $user;
                    $page = "emails.user.welcome";
                    $email = $user->email;
                    // Helper::send_email($page,$subject,$email,$email_data);

                    register_mobile($user->device_type);
                }

                $settings = Settings::where('key', 'wallet_bay_key')->first();

                $walletBayKey = $settings?$settings->value:'';


                //$referee_bonus = Settings::where('key','referee_bonus')->first();
                //$referee_bonus = $referee_bonus->value;
                $referrer_bonus = Settings::where('key','referrer_bonus')->first();
                $referrer_bonus = $referrer_bonus->value;

                 $referee_bonus = Settings::where('key','referee_bonus')->first();
                 $user->referee_bonus = $referee_bonus->value;
                 $user->save();



                // Response with registered user details:

                $response_array = array(
                    'success' => true,
                    'id' => $user->id,
                    'name' => $user->first_name.' '.$user->last_name,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'mobile' => $user->mobile,
                    'gender' => $user->gender,
                    'email' => $user->email,
                    'picture' => $user->picture,
                    'token' => $user->token,
                    'token_expiry' => $user->token_expiry,
                    'login_by' => $user->login_by,
                    'social_unique_id' => $user->social_unique_id,
                    'payment_mode_status' =>  $payment_mode_status,
                    'currency_code' => $user->currency_code,
                    'country' => $user->country,
                    'timezone' => $user->timezone,
                    'wallet_bay_key' => $walletBayKey,
                    'referral_code' => $user->referral_code,
                    'referee_bonus' => $user->referee_bonus,
                );

                $response_array = Helper::null_safe($response_array);

                Log::info('Registration completed');

            }

        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function login(Request $request)
    {
        $response_array = array();
        $operation = false;

        $device_type = $request->device_type;

        if($device_type == DEVICE_WEB) {
            $basicValidator = Validator::make(
            $request->all(),
            array(
                // 'device_token' => 'required',
                'device_type' => 'required|in:'.DEVICE_ANDROID.','.DEVICE_IOS.','.DEVICE_WEB,
                'login_by' => 'required|in:manual,facebook,google',
                )
            );
        }
        else 
        {
            $basicValidator = Validator::make(
            $request->all(),
            array(
                'device_token' => 'required',
                'device_type' => 'required|in:'.DEVICE_ANDROID.','.DEVICE_IOS.','.DEVICE_WEB,
                'login_by' => 'required|in:manual,facebook,google',
                )
            );
        }
        

        if($basicValidator->fails()){
            $error_messages = implode(',',$basicValidator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages'=> $error_messages);
        }else{

            $login_by = $request->login_by;
            if($login_by == 'manual'){

                /*validate manual login fields*/
                $manualValidator = Validator::make(
                    $request->all(),
                    array(
                        'email' => 'required|email',
                        'password' => 'required',
                    )
                );

                if ($manualValidator->fails()) {
                    $error_messages = implode(',',$manualValidator->messages()->all());
                    $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages'=> $error_messages);
                } else {

                    $email = $request->email;
                    $password = $request->password;

                    // Validate the user credentials
                    if($user = User::where('email', '=', $email)->first()){
                        if($user->is_activated) {
                            if(Hash::check($password, $user->password)){

                                /*manual login success*/
                                $operation = true;

                            }else{
                                $response_array = array( 'success' => false, 'error' => Helper::get_error_message(105), 'error_code' => 105 );
                            }
                        } else {
                            $response_array = array('success' => false , 'error' => Helper::get_error_message(144),'error_code' => 144);
                        }

                    } else {
                        $response_array = array( 'success' => false, 'error' => Helper::get_error_message(100), 'error_code' => 100 );
                    }
                }

            } else {
                /*validate social login fields*/
                $socialValidator = Validator::make(
                    $request->all(),
                    array(
                        'social_unique_id' => 'required',
                    )
                );

                if ($socialValidator->fails()) {
                    $error_messages = implode(',',$socialValidator->messages()->all());
                    $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages'=> $error_messages);
                } else {
                    $social_unique_id = $request->social_unique_id;
                    if ($user = User::where('social_unique_id', '=', $social_unique_id)->first()) {
                        if($user->is_activated) {
                            /*social login success*/
                            $operation = true;
                        } else {
                            $response_array = array('success' => false , 'error' => Helper::get_error_message(144),'error_code' => 144);
                        }

                    }else{
                        $response_array = array('success' => false, 'error' => Helper::get_error_message(125), 'error_code' => 125);
                    }

                }
            }

            if($operation){

                $device_token = $request->device_token;
                $device_type = $request->device_type;

                // Generate new tokens
                $user->token = Helper::generate_token();
                $user->token_expiry = Helper::generate_token_expiry();

                // Save device details
                $user->device_token = $device_token;
                $user->device_type = $device_type;
                $user->login_by = $login_by;
                $user->payment_mode = 'tron_wallet';
                $user->save();

                $payment_mode_status = $user->payment_mode ? $user->payment_mode : 0;

                $settings = Settings::where('key', 'wallet_bay_key')->first();

                $walletBayKey = $settings?$settings->value:'';

                $referee_bonus = $user->referee_bonus;


                // Respond with user details

                $response_array = array(
                    'success' => true,
                    'id' => $user->id,
                    'name' => $user->first_name.' '.$user->last_name,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'mobile' => $user->mobile,
                    'email' => $user->email,
                    'gender' => $user->gender,
                    'picture' => $user->picture,
                    'token' => $user->token,
                    'token_expiry' => $user->token_expiry,
                    'login_by' => $user->login_by,
                    'social_unique_id' => $user->social_unique_id,
                    'payment_mode_status' => $payment_mode_status,
                    'currency_code' => $user->currency_code,
                    'country' => $user->country,
                    'timezone' => $user->timezone,
                    'wallet_bay_key' => $walletBayKey,
                    'referral_code'=> $user->referral_code,
                    'referee_bonus' => $referee_bonus,
                );

                $response_array = Helper::null_safe($response_array);
            }
        }

        $response = response()->json($response_array, 200);
        return $response;
    }


    public function apply_referral(Request $request){
        $validator = Validator::make(
            $request->all(),
            array(
                'referral_code' => 'required',
            )
        );
        if ($validator->fails()) {
            $error_messages = implode(',',$validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages'=> $error_messages);
        }
        else
        {
            
            $referral_code = $request->referral_code;
            $check_ref = User::where('referral_code',$referral_code)->first();

            if(!$check_ref){
              $response_array = array('success' => false , 'error' => 'Invalid Referral Code', 'error_code' => 199);

            }else{

                //$referee_bonus = Settings::where('key','referee_bonus')->first();
                //$user->referee_bonus = $referee_bonus->value;
                $referrer_bonus = Settings::where('key','referrer_bonus')->first();
                $referrer_bonus = $referrer_bonus->value;
                $check_ref->referrer_bonus += $referrer_bonus;
                $check_ref->save();

                

                
                 $response_array = array('success' => true , 'message' => 'You got a referral bonus of '.$referrer_bonus);
            }

            $response = response()->json($response_array, 200);
            return $response;
        }

    }

    public function forgot_password(Request $request)
    {
        $email =$request->email;
        // Validate the email field
        $validator = Validator::make(
            $request->all(),
            array(
                'email' => 'required|email|exists:users,email',
            )
        );
        if ($validator->fails()) {
            $error_messages = implode(',',$validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages'=> $error_messages);
        }
        else
        {
            $user = User::where('email' , $email)->first();
            $new_password = Helper::generate_password();
            $user->password = Hash::make($new_password);

            $email_data = array();
            $subject = Helper::tr('user_forgot_email_title');
            $email_data['password']  = $new_password;
            $email_data['user']  = $user;
            $page = "emails.user.forgot_password";
            $email_send = Helper::send_email($page,$subject,$user->email,$email_data);

            $response_array['success'] = true;
            $response_array['message'] = Helper::get_message(106);
            $user->save();
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function change_password(Request $request) {

        $old_password = $request->old_password;
        $new_password = $request->password;
        $confirm_password = $request->confirm_password;

        $validator = Validator::make($request->all(), [
                'password' => 'required|confirmed',
                'old_password' => 'required',
            ]);

        if($validator->fails()) {
            $error_messages = implode(',',$validator->messages()->all());
            $response_array = array('success' => false, 'error' => 'Invalid Input', 'error_code' => 401, 'error_messages' => $error_messages );
        } else {
            $user = User::find($request->id);

            if(Hash::check($old_password,$user->password))
            {
                $user->password = Hash::make($new_password);
                $user->save();

                $response_array = Helper::null_safe(array('success' => true , 'message' => Helper::get_message(102)));

            } else {
                $response_array = array('success' => false , 'error' => Helper::get_error_message(131), 'error_code' => 131);
            }

        }

        $response = response()->json($response_array,200);
        return $response;

    }

    public function user_details(Request $request)
    {
        $user = User::find($request->id);

        $response_array = array(
            'success' => true,
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'mobile' => $user->mobile,
            'gender' => $user->gender,
            'email' => $user->email,
            'picture' => $user->picture,
            'token' => $user->token,
            'token_expiry' => $user->token_expiry,
            'login_by' => $user->login_by,
            'social_unique_id' => $user->social_unique_id,
            'referral_code' => $user->referral_code

        );
        $response = response()->json(Helper::null_safe($response_array), 200);
        return $response;
    }

    public function update_profile(Request $request)
    {
        $user_id = $request->id;

        $validator = Validator::make(
            $request->all(),
            array(
                'id' => 'required',
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'email|unique:users,email,'.$user_id.'|max:255',
                'mobile' => 'required|digits_between:6,13',
                'picture' => 'mimes:jpeg,bmp,png',
                'gender' => 'in:male,female,others',
                'device_token' => '',
            ));

        if ($validator->fails()) {
            // Error messages added in response for debugging
            $error_messages = implode(',',$validator->messages()->all());
            $response_array = array(
                    'success' => false,
                    'error' => Helper::get_error_message(101),
                    'error_code' => 101,
                    'error_messages' => $error_messages
            );
        } else {

            $name = $request->name;
            $email = $request->email;
            $mobile = $request->mobile;
            $picture = $request->file('picture');

            $user = User::find($user_id);
            if($request->has('first_name')) {
                $user->first_name = $request->first_name;
            }
            if($request->has('last_name')) {
                $user->last_name = $request->last_name;
            }
            if($request->has('email')) {
                $user->email = $email;
            }
            if ($mobile != "")
                $user->mobile = $mobile;
            // Upload picture
            if ($picture != "") {
                Helper::delete_picture($user->picture); // Delete the old pic
                $user->picture = Helper::upload_picture($picture);
            }
            if($request->has('gender')) {
                $user->gender = $request->gender;
            }
            if($request->has('timezone')) {
                $user->timezone = $request->timezone;
            } 

            // Generate new tokens
            // $user->token = Helper::generate_token();
            // $user->token_expiry = Helper::generate_token_expiry();

            $user->save();

            $payment_mode_status = $user->payment_mode ? $user->payment_mode : "";

            $response_array = array(
                'success' => true,
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'mobile' => $user->mobile,
                'gender' => $user->gender,
                'email' => $user->email,
                'picture' => $user->picture,
                'token' => $user->token,
                'token_expiry' => $user->token_expiry,
                'login_by' => $user->login_by,
                'social_unique_id' => $user->social_unique_id,
                'payment_mode_status' => $payment_mode_status
            );
            $response_array = Helper::null_safe($response_array);
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function send_otp(Request $request)
    {
        $phone      = "+".$request->mobile; 
        $checkPhone = User::where('mobile', $phone)->first();
        if(count($checkPhone) == 0) {
            $six_digit_random_number = mt_rand(100000, 999999);
            $message    = "Your one time verification code of ridey is $six_digit_random_number"; 
            //Helper::send_twilio_sms($phone, $message);
            $response_array = array('success'=>true,'code'=>$six_digit_random_number,'message'=>'OTP Code has been sent succesfully');
        } else {
            $response_array = array('success'=>false,'code'=>'','message'=>'The mobile number is in already use.');
        }
        $response = response()->json($response_array, 200);
        return $response;
    }

    public function token_renew(Request $request)
    {

        $user_id = $request->id;

        $token_refresh = $request->token;

        // Check if refresher token is valid

        if ($user = User::where('id', '=', $user_id)->where('token', '=', $token_refresh)->first()) {

            // Generate new tokens
            $user->token = Helper::generate_token();
            $user->token_expiry = Helper::generate_token_expiry();

            $user->save();
            $response_array = Helper::null_safe(array('success' => true,'token' => $user->token));
        } else {
            $response_array = array('success' => false,'error' => Helper::get_error_message(115),'error_code' => 115);
        }

        $response = response()->json($response_array, 200);
        return $response;

    }

    public function service_list_old(Request $request) {
        $time = $request->time;
        $distance = $request->distance;
        $service_tax_details = Settings::where('key','tax_price')->first();
        $serviceListed = array();
        $serviceList = ServiceType::orderBy('order', 'asc')->get();
        if($serviceList) {
            foreach($serviceList as $taken) {
                $serviceListValues['id'] = $taken->id;
                $serviceListValues['name'] = $taken->name;
                $serviceListValues['provider_name'] = $taken->provider_name;
                $serviceListValues['picture'] = $taken->picture;
                $serviceListValues['status'] = $taken->status;
                $serviceListValues['order'] = $taken->order;
                $serviceListValues['created_at'] = $taken->created_at;
                $serviceListValues['number_seat'] = $taken->number_seat;
                $serviceListValues['base_fare'] = $taken->base_fare;
                $serviceListValues['min_fare'] = $taken->min_fare;
                $serviceListValues['booking_fee'] = $taken->booking_fee;
                $serviceListValues['tax_fee'] = $taken->tax_fee;
                $serviceListValues['price_per_min'] = $taken->price_per_min;
                $serviceListValues['price_per_unit_distance'] = $taken->price_per_unit_distance;
                $serviceListValues['distance_unit'] = $taken->distance_unit;

              $timeMinutes = $time * 0.0166667; // from seconds to minutes
              $price_per_unit_time = $taken->price_per_min*$timeMinutes;

              $min_fare   = $taken->min_fare;
              $base_price = $taken->base_fare;
              $tax_fee    = $taken->tax_fee;

              $unit = $taken->distance_unit;
              if($unit == 'kms')
              {
                  $distanceKm = $distance;
                  $setdistance_price = $taken->price_per_unit_distance;
                  $price_per_unit_distance = $setdistance_price*$distanceKm;
              }
              else
              {
                  $distanceMiles = $distance * 0.621371; // from kms to miles
                  $setdistance_price = $taken->price_per_unit_distance;
                  $price_per_unit_distance = $setdistance_price*$distanceMiles;
              }
              $semi_total   = $base_price + $price_per_unit_distance + $price_per_unit_time + $taken->booking_fee;
              $total        = $semi_total * ($tax_fee/100) + $semi_total;
              $tax_fee      = $semi_total * ($tax_fee/100);

              // checking the total amount with minimum fare
              if($total <= $min_fare)
              {
                  $total = $min_fare;
                  $difference = 'least';
              }
              else {
                  $difference = 'greater';
              }

              $serviceListValues['estimated_fare'] = round($total, 2);
              $serviceListValues['difference'] = $difference;
              $serviceListValues['unit'] = $unit;
            array_push($serviceListed, $serviceListValues);
            }
            $setting = Settings::where('key','currency')->first();
            $response_array = Helper::null_safe(array('success' => true,'currency' => $setting->value,'services' => $serviceListed));
        } else {
            $response_array = array('success' => false,'error' => Helper::get_error_message(115),'error_code' => 115);
        }
        $response = response()->json($response_array, 200);
        return $response;

    }

    public function service_list(Request $request) {
        $time = $request->time;
        $distance = $request->distance;
        $service_tax_details = Settings::where('key','tax_price')->first();
        $serviceListed = array();
        $serviceList = ServiceType::orderBy('order', 'asc')->get(); //fetching all service types
        // $serviceList = Provider::where('providers.is_available',DEFAULT_TRUE)->where('waiting_to_respond',DEFAULT_FALSE)->where('is_activated',DEFAULT_TRUE)->where('is_approved',DEFAULT_TRUE)
        // ->leftJoin('provider_services', 'provider_services.provider_id', '=', 'providers.id')
        // ->leftJoin('service_types','provider_services.service_type_id', '=' ,'service_types.id')
        // ->select('service_types.*')->distinct()->get();
        //print_r($serviceList);exit;
        if($serviceList) {
            foreach($serviceList as $taken) {
                $serviceListValues['id'] = $taken->id;
                $serviceListValues['name'] = $taken->name;
                $serviceListValues['provider_name'] = $taken->provider_name;
                $serviceListValues['picture'] = $taken->picture;
                $serviceListValues['status'] = $taken->status;
                $serviceListValues['order'] = $taken->order;
                $serviceListValues['created_at'] = $taken->created_at;
                $serviceListValues['number_seat'] = $taken->number_seat;
                $serviceListValues['model'] = $taken->model;
                $serviceListValues['color'] = $taken->color;
                $serviceListValues['plate_no'] = $taken->plate_no;
                $serviceListValues['base_fare'] = $taken->base_fare;
                $serviceListValues['min_fare'] = $taken->min_fare;
                $serviceListValues['booking_fee'] = $taken->booking_fee;
                $serviceListValues['tax_fee'] = $taken->tax_fee;
                $serviceListValues['price_per_min'] = $taken->price_per_min;
                $serviceListValues['price_per_unit_distance'] = $taken->price_per_unit_distance;
                $serviceListValues['distance_unit'] = $taken->distance_unit;

              $timeMinutes = $time * 0.0166667; // from seconds to minutes
              $price_per_unit_time = $taken->price_per_min*$timeMinutes;

              $min_fare   = $taken->min_fare;
              $base_price = $taken->base_fare;
              $tax_fee    = $taken->tax_fee;

              $unit = $taken->distance_unit;
              if($unit == 'kms')
              {
                  $distanceKm = $distance;
                  $setdistance_price = $taken->price_per_unit_distance;
                  $price_per_unit_distance = $setdistance_price*$distanceKm;
              }
              else
              {
                  $distanceMiles = $distance * 0.621371; // from kms to miles
                  $setdistance_price = $taken->price_per_unit_distance;
                  $price_per_unit_distance = $setdistance_price*$distanceMiles;
              }
              $semi_total   = $base_price + $price_per_unit_distance + $price_per_unit_time + $taken->booking_fee;

              // checking the total amount with minimum fare
              if($semi_total <= $min_fare)
              {
                  $calc_tax_fee    = $min_fare * ($tax_fee/100);
                  $calc_tax_fee    = round($calc_tax_fee, 2);
                  $total        = $calc_tax_fee + $min_fare;
                  $difference = 'least';
              }
              else 
              {
                  $calc_tax_fee    = $semi_total * ($tax_fee/100);
                  $calc_tax_fee    = round($calc_tax_fee, 2);
                  $total        = $calc_tax_fee + $semi_total;
                  $difference = 'greater';
              }

              $serviceListValues['estimated_fare'] = round($total, 2);
              $serviceListValues['difference'] = $difference;
              $serviceListValues['unit'] = $unit;
            array_push($serviceListed, $serviceListValues);
            }

            /** user tron wallet details */
            $wallet = Tron::getUserWallet($request->id);
            $balance = Tron::getUserBalance($request->id);


            $setting = Settings::where('key','currency')->first();
            $response_array = Helper::null_safe([
                'success' => true,
                'tron_wallet' => $wallet,
                'tron_balance' => $balance  / pow(10, 6),
                'currency' => $setting->value,
                'services' => $serviceListed
            ]);

            
        } else {
            $response_array = array('success' => false,'error' => Helper::get_error_message(115),'error_code' => 115);
        }
        $response = response()->json($response_array, 200);
        return $response;

    }


    public function single_service(Request $request) {

        $validator = Validator::make(
                $request->all(),
                array(
                    'service_id' => 'required',
                ));

        if ($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        }
        else
        {
            if($serviceList = ServiceType::find($request->id))
            {
                $providerList = ProviderService::where('service_type_id',$request->id)->get();
                $provider_details = array();
                $provider_details_data = array();

                foreach ($providerList as $provider_details)
                {
                    $provider = Provider::find($provider_details->id);
                    $provider_details['id'] = $provider->id;
                    $provider_details['name'] = $provider->name;
                    $provider_details['latiude'] = $provider->latiude;
                    $provider_details['longitude'] = $provider->longitude;
                    $provider_details_data[] = $provider_details;
                    $provider_details = array();
                }
                $response_array = array('success' => true,'provider_details' => $provider_details_data);
                $response_array = Helper::null_safe($response_array);
            } else {
                $response_array = array('success' => false,'error' => Helper::get_error_message(115),'error_code' => 115);
            }
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function guest_provider_list(Request $request) {
        $validator = Validator::make(
            $request->all(),
            array(
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'service_id' => 'exists:service_types,id',
            ));

        if ($validator->fails()) {
            $error_messages = implode(',',$validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        } else {
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            /*Get default search radius*/
            $settings = Settings::where('key', 'search_radius')->first();
            $distance = $settings->value;

            $service_type_id = $request->service_id;

            if(!$request->service_id) {
                if($service_type = ServiceType::where('status' , DEFAULT_TRUE)->first()) {
                    $service_type_id = $service_type->id;
                }
            }

           $query = "SELECT DISTINCT providers.id,providers.first_name,providers.last_name,providers.latitude,providers.longitude,
                            1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) AS distance
                      FROM providers
                      LEFT JOIN provider_services ON providers.id = provider_services.provider_id
                      LEFT JOIN provider_ratings ON providers.id = provider_ratings.provider_id
                      WHERE provider_services.service_type_id = $service_type_id AND
                       providers.is_available = 1 AND providers.is_activated = 1 AND providers.is_approved = 1 AND (1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance
                      ORDER BY distance";




           $providers = DB::select(DB::raw($query));
          //  Log::info('search query'.print_r($query,true));
          //  Log::info("search provider:".print_r($providers,true));
           $provider_details = array();
           if($providers)
           {
              foreach ($providers as $provider) {
                  $provider_detail = array();
                  $provider_detail['id'] = $provider->id;
                  $provider_detail['first_name'] = $provider->first_name;
                  $provider_detail['last_name'] = $provider->last_name;
                  $provider_detail['latitude'] = $provider->latitude;
                  $provider_detail['longitude'] = $provider->longitude;
                  $provider_detail['distance'] = $provider->distance;
                  $provider_detail['rating'] = DB::table('user_ratings')->where('provider_id', $provider->id)->avg('rating') ?: 0;
                  array_push($provider_details,$provider_detail);
              }
           }

            $response_array = array(
                'success' => true,
                'providers' => $provider_details
            );
            // Log::info($response_array);

        }

        return response()->json($response_array , 200);
    }

    public function fare_calculator(Request $request){

      $validator = Validator::make(
          $request->all(),
          array(
            'distance' => 'required',
            'time' => 'required',
          ));

      if ($validator->fails()) {
          $error_messages = implode(',',$validator->messages()->all());
          $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
      } else {
          $time = $request->time; //as secs
          $distance = $request->distance; //as km

          if($request->has('service_id')){
              // Get base price from provider service table.
              $get_price_details = ServiceType::where('id',$request->service_id)->first();
              $timeMinutes = $time * 0.0166667; // from seconds to minutes
              $price_per_unit_time = $get_price_details->price_per_min*$timeMinutes;

              $min_fare   = $get_price_details->min_fare;
              $base_price = $get_price_details->base_fare;
              $tax_fee    = $get_price_details->tax_fee;
              $booking_fee    = $get_price_details->booking_fee;

              $unit = $get_price_details->distance_unit;
              if($unit == 'kms')
              {
                  $distanceKm = $distance;
                  $setdistance_price = $get_price_details->price_per_unit_distance;
                  $price_per_unit_distance = $setdistance_price*$distanceKm;
              }
              else
              {
                  $distanceMiles = $distance * 0.621371; // from kms to miles
                  $setdistance_price = $get_price_details->price_per_unit_distance;
                  $price_per_unit_distance = $setdistance_price*$distanceMiles;
              }
              $surge = Helper::checkSurge();
              //$semi_total   = $base_price + $price_per_unit_distance + $price_per_unit_time + $booking_fee + $surge;
              $semi_total   = ($base_price + $price_per_unit_distance + $price_per_unit_time) * $surge + $booking_fee;
          }
          else {
                $response_array = array('success' => false,'error' => Helper::get_error_message(162),'error_code' => 162);
                return response()->json($response_array , 200);
          }
          // checking the total amount with minimum fare
          if($semi_total <= $min_fare)
          {
              $calc_tax_fee    = $min_fare * ($tax_fee/100);
              $calc_tax_fee    = round($calc_tax_fee, 2);
              $total        = $calc_tax_fee + $min_fare;
              $difference = 'least';
          }
          else 
          {
              $calc_tax_fee    = $semi_total * ($tax_fee/100);
              $calc_tax_fee    = round($calc_tax_fee, 2);
              $total        = $calc_tax_fee + $semi_total;
              $difference = 'greater';
          }
          $setting = Settings::where('key','currency')->first();
          $response_array = Helper::null_safe(array(
            'success' => true,
            'estimated_fare' => round($total,2),
            'min_fare' => $min_fare,
            'base_price' => $base_price,
            'tax_price' => $calc_tax_fee,
            'booking_fee' => $booking_fee,
            'distance_unit' => $unit,
            'currency' => $setting->value,
            'difference' => $difference
          ));

      }

      return response()->json($response_array , 200);
    }

    public function fare_calculator_old(Request $request){

      $validator = Validator::make(
          $request->all(),
          array(
            'distance' => 'required',
            'time' => 'required',
          ));

      if ($validator->fails()) {
          $error_messages = implode(',',$validator->messages()->all());
          $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
      } else {
          $time = $request->time;
          $distance = $request->distance;
          // Tax price
          $service_tax_details = Settings::where('key','tax_price')->first();

          if($request->has('service_id')){
              // Get base price from provider service table.
              // Check in settings table single price for service is activated.
              $check_per_service_status = Settings::where('key','price_per_service')->first(); 
              if($check_per_service_status->value == 1){
                  $get_price_details = ServiceType::where('id',$request->service_id)->first();
                  $timeMinutes = $time * 0.0166667; // from seconds to minutes
                  $price_per_unit_time = $get_price_details->price_per_min*$timeMinutes;

                  $base_price = $get_price_details->min_fare;

                  $unit = $get_price_details->distance_unit;
                  if($unit == 'kms'){
                  $distanceKm = $distance;
                  $setdistance_price = $get_price_details->price_per_unit_distance;
                  $price_per_unit_distance = $setdistance_price*$distanceKm;
                  }else{
                  $distanceMiles = $distance * 0.621371; // from kms to miles
                  $setdistance_price = $get_price_details->price_per_unit_distance;
                  $price_per_unit_distance = $setdistance_price*$distanceMiles;
                  }
              }
              else{
                $response_array = array('success' => false,'error' => Helper::get_error_message(157),'error_code' => 157);
                return response()->json($response_array , 200);
              }
          }
          else {
              // Base price from settings table
              $getbase_price = Settings::where('key','base_price')->first();
              $base_price = $getbase_price->value;
              $distance_unit = Settings::where('key','default_distance_unit')->first();
              $unit = $distance_unit->value;
              if($unit == 'kms'){
              $distanceKm = $distance;
              $setdistance_price = Settings::where('key','price_per_unit_distance')->first();
              $price_per_unit_distance = $setdistance_price->value*$distanceKm;
              }else{
              $distanceMiles = $distance * 0.621371;
              $setdistance_price = Settings::where('key','price_per_unit_distance')->first();
              $price_per_unit_distance = $setdistance_price->value*$distanceMiles;
              }
              $timeMinutes = $time * 0.0166667;
              $settime_price = Settings::where('key','price_per_minute')->first();
              $price_per_unit_time = $settime_price->value*$timeMinutes;
          }
          // echo "price_per_unit_time ".$price_per_unit_time;
          // echo "<br>price_per_unit_distance ".$price_per_unit_distance;
          // echo "<br>base_price ".$base_price;
          // exit;
          $semi_total = $base_price+$price_per_unit_distance+$price_per_unit_time;
          $total = $semi_total * ($service_tax_details->value/100) +$semi_total;
          $tax_price = $semi_total * ($service_tax_details->value/100);
          // echo $total; exit;

          // if($total <= 25)
          // {
          //     $es_total_to = 25;
          //     $es_total_from = 50;
          // }
          // else
          // {
          //     $es_total = round($total,2);
          //     $es_total_from = $es_total + 25;
          //     $es_total_to = $es_total - 25;
          // }

          $response_array = Helper::null_safe(array(
            'success' => true,
            'estimated_fare' => round($total,2),
            'tax_price' => round($tax_price,2)
          ));

      }

      return response()->json($response_array , 200);
    }

    public function hourly_package_fare(Request $request)
    {
        $setting = Settings::where('key','currency')->first();
        $validator = Validator::make(
          $request->all(),
          array(
            'number_hours' => 'required|exists:hourly_packages,number_hours',
            'service_type' => 'required|numeric|exists:service_types,id',
          ),
          array('exists' => 'The :attribute is not a valid package, please try valid package'));

        if ($validator->fails()) {
            $error_messages = implode(',',$validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        } else {
            if($hourly_package_fare = HourlyPackage::where('number_hours',$request->number_hours)
                                        ->where('car_type_id',$request->service_type)->first()){

                $response_array = Helper::null_safe(array(
                    'success' => true,
                    'currency' => $setting->value,
                    'hourly_package_details' => $hourly_package_fare,
                  ));
            }else{
                $response_array = array('success' => false , 'error' => Helper::get_error_message(160) , 'error_code' => 160);
            }
        }
        return response()->json($response_array , 200);
    }

    public function airport_details(Request $request)
    {
        if($airport_details = AirportDetail::all()){

            $response_array = Helper::null_safe(array(
                'success' => true,
                'airport_details' => $airport_details,
              ));
        }else{
            $response_array = array('success' => false , 'error' => Helper::get_error_message(160) , 'error_code' => 160);
        }
        return response()->json($response_array , 200);
    }

    public function location_details(Request $request)
    {
        $validator = Validator::make(
          $request->all(),
          array(
            'key' => 'required',
          ));

        if ($validator->fails()) {
            $error_messages = implode(',',$validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        } else {
            if($location_details = LocationDetail::where('name','like', '%'.$request->key.'%')->get()) {

                $response_array = Helper::null_safe(array(
                    'success' => true,
                    'location_details' => $location_details,
                  ));
            }else{
                $response_array = array('success' => false , 'error' => Helper::get_error_message(160) , 'error_code' => 160);
            }
        }
        return response()->json($response_array , 200);
    }


    public function airport_package_fare(Request $request)
    {
        $setting = Settings::where('key','currency')->first();
        $validator = Validator::make(
          $request->all(),
          array(
            'airport_details_id' => 'required|exists:airport_details,id',
            'location_details_id' => 'required|exists:location_details,id',
            'service_type' => 'required|exists:service_types,id',
          ),
          array('exists' => 'The :attribute is not a valid package, please try valid package'));

        if ($validator->fails()) {
            $error_messages = implode(',',$validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        } else {
            if($airport_package_fare = AirportPrice::where('airport_details_id',$request->airport_details_id)
                                        ->where('location_details_id',$request->location_details_id)
                                        ->where('service_type_id',$request->service_type)->first()){

                $response_array = Helper::null_safe(array(
                    'success' => true,
                    'currency' => $setting->value,
                    'airport_price_details' => $airport_package_fare,
                  ));
            }else{
                $response_array = array('success' => false , 'error' => Helper::get_error_message(160) , 'error_code' => 160);
            }
        }
        return response()->json($response_array , 200);
    }

    public function request_later(Request $request) {

        // $current_date = Helper::add_date(date('Y-m-d H:00:00') ,2); // To check the income date is greater than the current date

        $validator = Validator::make(
                $request->all(),
                array(
                    'latitude' => 'required|numeric',
                    'longitude' => 'required|numeric',
                    'service_type' => 'numeric|exists:service_types,id',
                    'requested_time' => 'required',
                    'hourly_package_id' => 'numeric|exists:hourly_packages,id',
                    'airport_price_id' => 'numeric|exists:airport_prices,id',
                    'request_status_type' => 'numeric'
                ), array( 'required' => 'Location Selected was incorrect! Please try again!',
                          'exists' => 'Invalid package, please try valid package'));

        if ($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        } else {
            Log::info('Create request start');

            // Check the user filled the payment details
            $user = User::find($request->id);
            $userTimezone = $user->timezone;

            if(!$user->payment_mode) {
                // Log::info('Payment Mode is not available');
                $response_array = array('success' => false , 'error' => Helper::get_error_message(134) , 'error_code' => 134);
            } else {

                $allow = DEFAULT_FALSE;
                // if the payment mode is CARD , check if any default card is available
                if($user->payment_mode == CARD) {
                    if($user_card = Cards::find($user->default_card)) {
                        $allow = DEFAULT_TRUE;
                    }
                } else {
                    $allow = DEFAULT_TRUE;
                }

                if($allow == DEFAULT_TRUE) {

                    $hourly_package_valid = DEFAULT_FALSE;
                    $airport_package_valid = DEFAULT_FALSE;
                    $request_status_type = DEFAULT_FALSE;

                    //Check request status type for hourly package,airport and normal request.
                    if($request->has('request_status_type')){
                        if($request->request_status_type == 1){
                            $request_status_type = NORMAL_REQUEST;
                        }elseif ($request->request_status_type == 2) {
                            $request_status_type = HOURLY_PACKAGE;
                        }elseif ($request->request_status_type == 3) {
                            $request_status_type = AIRPORT_PACKAGE;
                        }
                    }else{
                        $request_status_type = NORMAL_REQUEST;
                    }

                    // Check for hourly package status
                    if($request_status_type == HOURLY_PACKAGE){
                        if($request->has('hourly_package_id')){
                            if($hourly_package_details = HourlyPackage::where('id',$request->hourly_package_id)->first()){
                                $hourly_package_valid = DEFAULT_TRUE;
                            }else{
                                $response_array = array('success' => false , 'error' => Helper::get_error_message(142) ,'error_code' => 142);
                                $response = response()->json($response_array, 200);
                                return $response;
                            }
                        }
                    } elseif($request_status_type == AIRPORT_PACKAGE) {
                        if($request->has('airport_price_id')) {
                            $airport_price_details = AirportPrice::where('id',$request->airport_price_id)->first(); 
                            $airport_package_valid = DEFAULT_TRUE;
                        }
                    }
                    

                    // Check already normal request exists
                    $check_status = array(REQUEST_NO_PROVIDER_AVAILABLE,REQUEST_CANCELLED,REQUEST_TIME_EXCEED_CANCELLED,REQUEST_COMPLETED);

                    $check_requests = Requests::where('user_id' , $request->id)->whereNotIn('status' , $check_status)->where('later' , DEFAULT_FALSE)->count();

                    if($check_requests == 0) {

                        // Check any scheduled requests from current time +2hours , -1hour
                        $check_later_requests = Helper::check_later_request($request->id,$request->requested_time,DEFAULT_TRUE);

                        if(!$check_later_requests) {

                                Log::info('Previous requests check is done');
                                $service_type = $request->service_type; // Get the service type


                                $latitude = $request->latitude;
                                $longitude = $request->longitude;
                                $request_start_time = time();


                                /*Get default search radius*/
                                $settings = Settings::where('key', 'search_radius')->first();
                                $distance = $settings->value;



                                /*************************************/

                                $requested_date = Helper::formatDate($request->requested_time);
                                $requested_time = $start_time = Helper::formatHour($request->requested_time);

                                // get the +2 hours from the requested time
                                $change_date = new \DateTime($request->requested_time);
                                $change_date->modify("+1 hours");
                                $end_time = $change_date->format("H:i:s");

                                $next_start_time = $end_time;
                                $next_end_date = Helper::add_date($request->requested_time,'+2');
                                $next_end_time = Helper::formatHour($next_end_date);


                                // Create Requests
                                $requests = new Requests;
                                $requests->user_id = $user->id;

                                if($service_type)
                                    $requests->request_type = $service_type;

                                $requests->status = REQUEST_NEW;
                                $requests->confirmed_provider = NONE;
                                $requests->s_address = $request->s_address ? $request->s_address : "";
                                $requests->d_address = $request->d_address ? $request->d_address : "";
                                $requests->d_latitude = $request->d_latitude ? $request->d_latitude : "";
                                $requests->d_longitude = $request->d_longitude ? $request->d_longitude : "";

                                //Later Details
                                $requests->later = DEFAULT_TRUE;

                                // Time converstion
                                $requests->requested_time = Helper::convertTimeToUTCzone($request->requested_time, $userTimezone, $format = 'Y-m-d H:i:s');

                                if($latitude){ $requests->s_latitude = $latitude; }
                                if($longitude) { $requests->s_longitude = $longitude; }

                                if($request_status_type == HOURLY_PACKAGE && $hourly_package_valid == DEFAULT_TRUE){
                                    $requests->request_status_type = HOURLY_PACKAGE;
                                    $requests->hourly_package_id = $request->hourly_package_id;
                                }elseif($request_status_type == AIRPORT_PACKAGE && $airport_package_valid == DEFAULT_TRUE){
                                    $requests->request_status_type = AIRPORT_PACKAGE;
                                    $requests->airport_price_id = $request->airport_price_id;
                                }else {
                                    $requests->request_status_type = NORMAL_REQUEST;
                                }

                                $requests->save();

                                $response_d_address = $requests->d_address ? $requests->d_address : "";
                                $response_d_latitude = $requests->d_latitude ? $requests->d_latitude : "";
                                $response_d_longitude = $requests->d_longitude ? $requests->d_longitude : "";


                                    $response_array = array(
                                        'success' => true,
                                        'request_id' => $requests->id,
                                        'address' => $requests->s_address,
                                        'latitude' => $requests->s_latitude,
                                        'longitude' => $requests->s_longitude,
                                        'd_address' => $response_d_address,
                                        'd_latitude' => $response_d_latitude,
                                        'd_longitude' => $response_d_longitude,
                                    );

                                    $response_array = Helper::null_safe($response_array); Log::info('Create request end');

                                } else {
                                    $response_array = array('success' => false, 'error' => Helper::get_error_message(112), 'error_code' => 112);
                                }

                            } else {
                                $response_array = array('success' => false, 'error' => Helper::get_error_message(112), 'error_code' => 112);
                            }

                        } else {
                            $response_array = array('success' => false , 'error' => Helper::get_error_message(150) , 'error_code' => 150);
                        }

                //     } else {
                //         $response_array = array('success' => false , 'error' => Helper::get_error_message(127) , 'error_code' => 127);
                //     }
                //
                // } else {
                //     $response_array = array('success' => false , 'error' => Helper::get_error_message(142) ,'error_code' => 142);
                // }


              }

            }


        $response = response()->json($response_array, 200);
        return $response;

    }

    public function get_upcoming_request(Request $request) {

        $requests = Requests::where('requests.user_id' , $request->id)
                        ->where('requests.later' , DEFAULT_TRUE)
                        ->where('requests.status' , REQUEST_NEW)
                        ->leftJoin('users', 'users.id', '=', 'requests.user_id')
                        ->leftJoin('providers', 'providers.id', '=', 'requests.confirmed_provider')
                        ->leftJoin('service_types', 'service_types.id', '=', 'requests.request_type')
                        ->select('requests.id as request_id','requests.later','requests.requested_time', 'requests.request_type as request_type', 'service_types.name as service_type_name', 'request_start_time as request_start_time', 'requests.status','requests.confirmed_provider as provider_id', DB::raw('CONCAT(providers.first_name, " ", providers.last_name) as provider_name'),'providers.picture as provider_picture','requests.provider_status', 'requests.amount', DB::raw('CONCAT(users.first_name, " ", users.last_name) as user_name'), 'users.picture as user_picture', 'users.id as user_id','requests.s_latitude', 'requests.s_longitude','requests.s_address','requests.d_address','service_types.picture as type_picture','requests.d_latitude',
                            'requests.d_longitude')
                        ->get();

        $request_details = array();
        $user = User::find($request->id);
        $userTimezone = $user->timezone;
        foreach($requests as $req){
            $request_det = array();
            $request_det['request_id'] = $req->request_id;
            $request_det['later'] = $req->later;
            $request_det['request_type'] = $req->request_type;
            $request_det['service_type_name'] = $req->service_type_name;
            $request_det['provider_id'] = $req->provider_id;
            $request_det['provider_picture'] = $req->provider_picture;
            $request_det['provider_status'] = $req->provider_status;
            $request_det['amount'] = $req->amount;
            $request_det['user_name'] = $req->user_name;
            $request_det['user_picture'] = $req->user_picture;
            $request_det['user_id'] = $req->user_id;
            $request_det['s_latitude'] = $req->s_latitude;
            $request_det['s_longitude'] = $req->s_longitude;
            $request_det['d_latitude'] = $req->d_latitude;
            $request_det['d_longitude'] = $req->d_longitude;
            $request_det['type_picture'] = $req->type_picture;
            $request_det['status'] = $req->status;
            $request_det['s_address'] = $req->s_address;
            $request_det['d_address'] = $req->d_address;
            $request_det['requested_time'] = Helper::convertTimeToUSERzone($req->requested_time, $userTimezone, $format = 'Y-m-d H:i:s');
            $request_det['request_start_time'] = Helper::convertTimeToUSERzone($req->request_start_time, $userTimezone, $format = 'Y-m-d H:i:s');
            $request_det['provider_name'] = $req->provider_name;
            array_push($request_details, $request_det);
        }
        $request_details = Helper::null_safe($request_details);

        if($requests) {
            $response_array = array('success' => true , 'data' => $request_details);
        } else {
            $response_array = array('success' => false ,'error' => Helper::get_error_message(130) , 'error_code' => 130);
        }

        return response()->json(Helper::null_safe($response_array),200);

    }

    public function cancel_later_request(Request $request){

        $user_id = $request->id;

        $validator = Validator::make(
            $request->all(),
            array(
                'request_id' => 'required|numeric|exists:requests,id,user_id,'.$user_id,
            ));

        if ($validator->fails()){
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages'=>$error_messages);
        }else{

            $request_id = $request->request_id;
            $requests = Requests::find($request_id);
            $requestStatus = $requests->status;
            $providerStatus = $requests->provider_status;
            $allowedCancellationStatuses = array(
                PROVIDER_ACCEPTED,
                PROVIDER_STARTED,
            );

            // Check whether request cancelled previously
            if($requestStatus != REQUEST_CANCELLED)
            {

                // Update status of the request to cancellation
                $requests->status = REQUEST_CANCELLED;
                $requests->save();

                $response_array = Helper::null_safe(array('success' => true,'request_id' => $request->id));

            } else {
                $response_array = array( 'success' => false, 'error' => Helper::get_error_message(113), 'error_code' => 113 );
            }
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    // Automated Request
    public function send_request(Request $request) {

        Log::info('send_request'.print_r($request->all() ,true));

        $validator = Validator::make(
                $request->all(),
                array(
                    's_latitude' => 'required|numeric',
                    's_longitude' => 'required|numeric',
                    'service_type' => 'numeric|exists:service_types,id',
                    'hourly_package_id' => 'numeric|exists:hourly_packages,id',
                    'airport_price_id' => 'numeric|exists:airport_prices,id',
                    'request_status_type' => 'numeric',
                ), array( 'required' => 'Location Selected was incorrect! Please try again!',
                          'exists' => 'Invalid package, please try valid package'));

        if ($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        } else {

             $debt = Debt::where('user_id',$request->id)->first();
            if($debt){
                if($debt->allow != 1 ){
                 Log::info('Debt pending');
                 goto outstanding;
                }
                
            }

            Log::info('Create request start');
            // Check the user filled the payment details

            $user = User::find($request->id);
            // Save the user location
            $user->latitude = $request->s_latitude;
            $user->longitude = $request->s_longitude;
            $user->payment_mode = 'tron_wallet'; //default tron_wallet is set
            $user->save();

            if(!$user->payment_mode) {
                // Log::info('Payment Mode is not available');
                $response_array = array('success' => false , 'error' => Helper::get_error_message(134) , 'error_code' => 134);
            } else {

                $allow = DEFAULT_TRUE;
                // if the payment mode is CARD , check if any default card is available
                /* if($user->payment_mode == CARD) {
                    if($user_card = Cards::find($user->default_card)) {
                        $allow = DEFAULT_TRUE;
                    }
                } else {
                    $allow = DEFAULT_TRUE;
                } */

                /* if(!Cards::find($user->default_card)) {
                    $response_array = array('success' => false , 'error' => "No Primary Card available to purchase TRX. Please Add a Payment Card" ,'error_code' => 5837);
                    $response = response()->json($response_array, 200);
                    return $response;
                } */


                /** if user payment mode is tron_wallet then check balalance */
                if($user->payment_mode == 'tron_wallet') {

                    $balance = Tron::getUserBalance($request->id);
                    $balance = $balance / pow(10, 6);
                    $percentage = 20;
                    $requiredBalance = $request->estimated_fare + $request->estimated_fare * ($percentage / 100);

                    \Log::info($balance);
                    \Log::info($requiredBalance);

                    if($balance < $requiredBalance) {
                        $response_array = array('success' => false , 'error' => "Please maintain minimum wallet balance of TRX {$requiredBalance}" ,'error_code' => 5837);
                        $response = response()->json($response_array, 200);
                        return $response;
                    }


                }


                if($user->payment_mode == WALLETBAY){

                    $settings = Settings::where('key', 'wallet_bay_key')->first();
                    $wallet_bay_key = $settings->value;
                    $wallet_url = Settings::where('key', 'wallet_url')->first();
                    $wallet_url = $wallet_url->value;

                    $headers = array('Authorization:'.$wallet_bay_key);

                    $ch = curl_init($wallet_url.'/api/businesses/users/'.$user->id.'/balance');
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

                    // execute!
                    $response = curl_exec($ch);

                    // close the connection, release resources used
                    curl_close($ch);

                    $data = \GuzzleHttp\json_decode($response);

                    if($data->data->user_balance <= 0){
                        goto walletLow;
                    }


                }


                $hourly_package_valid = DEFAULT_FALSE;
                $airport_package_valid = DEFAULT_FALSE;
                $request_status_type = DEFAULT_FALSE;

                if($allow == DEFAULT_TRUE) {

                    //Check request status type for hourly package,airport and normal request.
                    if($request->has('request_status_type')){
                        if($request->request_status_type == 1){
                            $request_status_type = NORMAL_REQUEST;
                        }elseif ($request->request_status_type == 2) {
                            $request_status_type = HOURLY_PACKAGE;
                        }elseif ($request->request_status_type == 3) {
                            $request_status_type = AIRPORT_PACKAGE;
                        }
                    }else{
                        $request_status_type = NORMAL_REQUEST;
                    }

                    // Check for hourly package status
                    if($request_status_type == HOURLY_PACKAGE){
                        if($request->has('hourly_package_id')){
                            if($hourly_package_details = HourlyPackage::where('id',$request->hourly_package_id)->first()){
                                $hourly_package_valid = DEFAULT_TRUE;
                            }else{
                                $response_array = array('success' => false , 'error' => Helper::get_error_message(142) ,'error_code' => 142);
                                $response = response()->json($response_array, 200);
                                return $response;
                            }
                        }
                    } elseif($request_status_type == AIRPORT_PACKAGE) {
                        if($request->has('airport_price_id')) {
                            $airport_price_details = AirportPrice::where('id',$request->airport_price_id)->first(); 
                            $airport_package_valid = DEFAULT_TRUE;
                        }
                    }

                    // Check already request exists
                    $check_status = array(REQUEST_NO_PROVIDER_AVAILABLE,REQUEST_CANCELLED,REQUEST_COMPLETED);

                    $check_requests = Requests::where('user_id' , $request->id)->whereNotIn('status' , $check_status)->where('later' , 0)->count();

                    if($check_requests == 0) {

                        // Check any scheduled requests from current time +2hours , -1hour
                        $check_later_requests = Helper::check_later_request($request->id,date('Y-m-d H:i:s'),DEFAULT_TRUE);

                        if(!$check_later_requests) {

                            Log::info('Previous requests check is done');
                            $service_type = $request->service_type; // Get the service type

                            // Initialize the variable
                            $first_provider_id = 0;

                            $latitude = $request->s_latitude;
                            $longitude = $request->s_longitude;
                            $request_start_time = time();

                            /*Get default search radius*/
                            $settings = Settings::where('key', 'search_radius')->first();
                            $distance = $settings->value;

                            // Search Providers
                            $providers = array();   // Initialize providers variable

                            // Check the service type value to search the providers based on the nearby location
                            if($service_type) {

                                Log::info('Location Based search started - service_type');
                                // Get the providers based on the selected service types

                                $service_providers = ProviderService::where('service_type_id' , $service_type)->where('is_available' , 1)->select('provider_id')->get();
                                Log::info("Service Providers: ".print_r($service_providers,true));
                                $list_service_ids = array();    // Initialize list_service_ids
                                if($service_providers) {
                                    foreach ($service_providers as $sp => $service_provider) {
                                        $list_service_ids[] = $service_provider->provider_id;
                                    }
                                    $list_service_ids = implode(',', $list_service_ids);
                                }
                                
                                if($list_service_ids) {
                                    $query = "SELECT providers.id,providers.waiting_to_respond as waiting, 1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) AS distance FROM providers
                                            WHERE id IN ($list_service_ids) AND is_available = 1 AND is_activated = 1 AND is_approved = 1
                                            AND (1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance
                                            ORDER BY distance";

                                    $providers = DB::select(DB::raw($query));
                                    Log::info("Query: ".$query);
                                }
                            } else {
                                Log::info('Location Based search started - without service_type');

                                $query = "SELECT providers.id,providers.waiting_to_respond as waiting, 1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) AS distance FROM providers
                                        WHERE is_available = 1 AND is_activated = 1 AND is_approved = 1
                                        AND (1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance
                                        ORDER BY distance";
                                $providers = DB::select(DB::raw($query));

                            }
                            // Log::info('List of providers'." ".print_r($providers));

                            // Initialize Final list of provider variable
                            $search_providers = array();
                            $search_provider = array();
                            Log::info("Provider list: ".print_r($providers,true));
                            if ($providers) {

                                foreach ($providers as $provider) {
                                    $search_provider['id'] = $provider->id;
                                    $search_provider['waiting'] = $provider->waiting;
                                    $search_provider['distance'] = $provider->distance;

                                    array_push($search_providers, $search_provider);
                                }
                            } else {
                                if(!$search_providers) {
                                    Log::info("No Provider Found");
                                    // Send push notification to User

                                    $title = Helper::get_push_message(601);
                                    $messages = Helper::get_push_message(602);
                                    $this->dispatch( new NormalPushNotification($user->id, 0,$title, $messages));
                                    $response_array = array('success' => false, 'error' => Helper::get_error_message(112), 'error_code' => 112);
                                }
                            }


                            // Sort the providers based on the waiting time
                            $sort_waiting_providers = Helper::sort_waiting_providers($search_providers);

                            // Get the final providers list
                            $final_providers = $sort_waiting_providers['providers'];
                                    //
                            $check_waiting_provider_count = $sort_waiting_providers['check_waiting_provider_count'];

                            if(count($final_providers) == $check_waiting_provider_count){
                                return response()->json($response_array = array('success' => false, 'error' => Helper::get_error_message(112), 'error_code' => 112) , 200);

                            }

                            /*Promo Section*/
                            // $getpromo_code = '';
                            // $getpromo_scope = '';
                            // $current = date('Y-m-d h:i:s');
                            // $getPromoOffer = PromoCode::where('scope', GLOBAL_OFFER)->whereRaw('"'.$current.'" between `start` and `end`')->first();
                            // Log::info("***** Promo Code Validation *****");
                            // Log::info("Checking global offer: ".count($getPromoOffer));
                            // if($getPromoOffer) 
                            // {
                            //     Log::info("Global offer id is: ".$getPromoOffer->id);
                            //     $getpromo_code = $getPromoOffer->id; // auto increment Id from promo table
                            //     $getpromo_scope = GLOBAL_OFFER;
                            // } 
                            // else 
                            // {
                            //     if($request->promo_code !='') 
                            //     {
                            //         $getPromo = PromoCode::where('coupon_code', $request->promo_code)->where('scope', LIMITED_OFFER)->whereRaw('"'.$current.'" between `start` and `end`')->first();
                            //         if($getPromo) {
                            //             $getpromo_code = $request->promo_code;
                            //             $getpromo_scope = LIMITED_OFFER;
                            //         } else {
                            //             Log::info("Invalid promo code given: ".$request->promo_code);
                            //         }
                            //     }
                            // }

                            // Create Requests
                            $requests = new Requests;
                            $requests->user_id = $user->id;

                            if($service_type)
                                $requests->request_type = $service_type;

                            $requests->surge = Helper::checkSurge(); // check and save surge value 

                            $requests->status = REQUEST_NEW;
                            $requests->confirmed_provider = NONE;
                            if($request->promo_code) {
                                $requests->promo_code = $request->promo_code;
                            }
                            $requests->request_start_time = date("Y-m-d H:i:s", $request_start_time);
                            $requests->s_address = $request->s_address ? $request->s_address : "";
                            $requests->d_address = $request->d_address ? $request->d_address : "";
                            $requests->d_latitude = $request->d_latitude ? $request->d_latitude : "";
                            $requests->d_longitude = $request->d_longitude ? $request->d_longitude : "";
                            $requests->is_adstop = $request->is_adstop ? $request->is_adstop : 0;
                            $requests->adstop_address = $request->adstop_address ? $request->adstop_address : "";
                            $requests->adstop_latitude = $request->adstop_latitude ? $request->adstop_latitude : "";
                            $requests->adstop_longitude = $request->adstop_longitude ? $request->adstop_longitude : "";

                            if($request_status_type == HOURLY_PACKAGE && $hourly_package_valid == DEFAULT_TRUE){
                                $requests->request_status_type = HOURLY_PACKAGE;
                                $requests->hourly_package_id = $request->hourly_package_id;
                            }elseif($request_status_type == AIRPORT_PACKAGE && $airport_package_valid == DEFAULT_TRUE){
                                $requests->request_status_type = AIRPORT_PACKAGE;
                                $requests->airport_price_id = $request->airport_price_id;
                            }else{
                                $requests->request_status_type = NORMAL_REQUEST;
                            }

                            if($latitude){ $requests->s_latitude = $latitude; }
                            if($longitude) { $requests->s_longitude = $longitude; }

                            $requests->save();

                            if($requests) {
                                $requests->status = REQUEST_WAITING;
                                //No need fo current provider state
                                // $requests->current_provider = $first_provider_id;
                                $requests->save();

                                // Save all the final providers
                                $first_provider_id = 0;

                                if($final_providers) {
                                    foreach ($final_providers as $key => $final_provider) {

                                        $request_meta = new RequestsMeta;

                                        if($first_provider_id == 0) {

                                            $first_provider_id = $final_provider;

                                            $request_meta->status = REQUEST_META_OFFERED;  // Request status change

                                            // Availablity status change
                                            if($current_provider = Provider::find($first_provider_id)) {
                                                $current_provider->waiting_to_respond = WAITING_TO_RESPOND;
                                                $current_provider->save();
                                            }

                                            // Send push notifications to the first provider
                                            $title = Helper::get_push_message(604);
                                            $message = "You got a new request from".$user->first_name." ".$user->last_name;

                                            $this->dispatch(new sendPushNotification($first_provider_id,2,$requests->id,$title,$message,''));

                                            // Push End
                                        }

                                        $request_meta->request_id = $requests->id;
                                        $request_meta->provider_id = $final_provider;
                                        $request_meta->save();
                                    }
                                }

                                $response_d_address = $requests->d_address ? $requests->d_address : "";
                                $response_d_latitude = $requests->d_latitude ? $requests->d_latitude : "";
                                $response_d_longitude = $requests->d_longitude ? $requests->d_longitude : "";

                                $response_array = array(
                                    'success' => true,
                                    'request_id' => $requests->id,
                                    'current_provider' => $first_provider_id,
                                    'address' => $requests->s_address,
                                    'latitude' => $requests->s_latitude,
                                    'longitude' => $requests->s_longitude,
                                    'd_address' => $response_d_address,
                                    'd_latitude' => $response_d_latitude,
                                    'd_longitude' => $response_d_longitude,
                                );

                                $response_array = Helper::null_safe($response_array); Log::info('Create request end');
                            } else {
                                $response_array = array('success' => false , 'error' => Helper::get_error_message(126) , 'error_code' => 126 );
                            }
                        } else {
                            $response_array = array('success' => false , 'error' => Helper::get_error_message(150) , 'error_code' => 150);
                        }
                    } else {
                        $response_array = array('success' => false , 'error' => Helper::get_error_message(127) , 'error_code' => 127);
                    }

                } else {
                    $response_array = array('success' => false , 'error' => Helper::get_error_message(142) ,'error_code' => 142);
                }
            }
        }
        $response = response()->json($response_array, 200);
        return $response;

        walletLow:
            $response_array = array('success' => false , 'error' => Helper::get_error_message(163) ,'error_code' => 163);
            $response = response()->json($response_array, 200);
            return $response;

        outstanding:
            $settings = Settings::where('key', 'accept_debt_cash')->first();
            $accept_debt_cash = $settings->value;

            $response_array = array('success' => false,
                                    'amount' => $debt->amount,
                                    'accept_cash' => $accept_debt_cash,
                                    'error' => Helper::get_error_message(166) ,
                                    'error_code' => 166);

            $response = response()->json($response_array, 200);
            return $response;

    }

    public function clearDebt(Request $request)
    {
        $validator = Validator::make(
                $request->all(),
                array(
                    'payment_mode' => 'required',
                    'card_id' => 'required',
                ));

        if ($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        } else {

            if ($request->payment_mode == "cash"){
                $user = User::find($request->id);
                $user->payment_mode = COD;
                $user->save();
                $debt = Debt::where('user_id',$request->id)->first();
                $debt->allow = 1;
                $debt->save();
                $response_array = array('success' => true);

            }elseif($request->payment_mode == "card"){
                // Card payment of debt happens here
                $user_card = Cards::find($request->card_id);
                $debt = Debt::where('user_id',$request->id)->first();

                $total = $debt->amount;
                if($total != 0){
                            //BRAINTREE PAYMENT
                          $transaction = Helper::createTransaction($user_card->customer_id,$debt->request_id,$total);

                          if($transaction == '0'){
                            $response_array = array('success' => false, 'error' => Helper::get_error_message(158) , 'error_code' => 158);
                            return response()->json($response_array , 200);
                          }
                          else {
                              $request_payment->status = DEFAULT_TRUE;
                              $request_payment->payment_id = $transaction;
                              $requests->is_paid = DEFAULT_TRUE;
                              $requests->status = REQUEST_RATING;
                              $requests->amount = $total;

                              $user->cancellation_charges = 0;
                              $user->save();

                              $debt->delete();

                              $response_array = array('success' => true);

                          }
                      }else {
                          $response_array = array('success' => false, 'error' => Helper::get_error_message(158) , 'error_code' => 159);
                      }

            }

            $response = response()->json($response_array , 200);
            return $response;



        }

    }

    public function message_notification(Request $request) {

        $validator = Validator::make(
                $request->all(),
                array(
                    'request_id' => 'required|exists:requests,id',
                ));

        if ($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        } else {
            //Check if the request is in progress state 
            if($request_details = Requests::where('id',$request->request_id)->where('confirmed_provider','!=',0)->first()) {
                //Send notification to provider
                $title = "You got a message from User";
                $message = "You got a message from User";

                $this->dispatch(new sendPushNotification($request_details->confirmed_provider,2,$request_details->id,$title,$message,'2'));
                // Push end
                $response_array = Helper::null_safe(array('success' => true));
            } else {
                $response_array = Helper::null_safe(array('success' => false));
            }
        }
        $response = response()->json($response_array , 200);
        return $response;
    }

    // Manual request
    public function manual_create_request(Request $request) {

        $validator = Validator::make(
                $request->all(),
                array(
                    's_latitude' => 'required|numeric',
                    's_longitude' => 'required|numeric',
                    'service_type' => 'numeric|exists:service_types,id',
                    'provider_id' => 'required|exists:providers,id',
                ));

        if ($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        } else {
            Log::info('Create request start');
            // Check the user filled the payment details
            $user = User::find($request->id);
            if(!$user->payment_mode) {
                Log::info('Payment Mode is not available');
                $response_array = array('success' => false , 'error' => Helper::get_error_message(134) , 'error_code' => 134);
            } else {

                $allow = DEFAULT_FALSE;
                // if the payment mode is CARD , check if any default card is available
                if($user->payment_mode == CARD) {
                    if($user_card = Cards::find($user->default_card)) {
                        $allow = DEFAULT_TRUE;
                    }
                } else {
                    $allow = DEFAULT_TRUE;
                }

                if($allow == DEFAULT_TRUE) {

                    // Check the provider is available
                    if($provider = Provider::where('id' , $request->provider_id)->where('is_available' , DEFAULT_TRUE)->where('is_activated' , DEFAULT_TRUE)->where('is_approved' , DEFAULT_TRUE)->where('waiting_to_respond' ,DEFAULT_FALSE)->first()) {

                        // Check already request exists
                        $check_status = array(REQUEST_NO_PROVIDER_AVAILABLE,REQUEST_CANCELLED,REQUEST_COMPLETED);

                        $check_requests = Requests::where('user_id' , $request->id)->whereNotIn('status' , $check_status)->count();

                        if($check_requests == 0) {

                            Log::info('Previous requests check is done');

                            // Create Requests
                            $requests = new Requests;
                            $requests->user_id = $user->id;

                            if($request->service_type)
                                $requests->request_type = $request->service_type;

                            $requests->status = REQUEST_NEW;
                            $requests->confirmed_provider = NONE;
                            $requests->request_start_time = date("Y-m-d H:i:s");
                            $requests->s_address = $request->s_address ? $request->s_address : "";
                            $requests->d_address = $request->d_address ? $request->d_address : "";
                            $requests->d_latitude = $request->d_latitude ? $request->d_latitude : "";
                            $requests->d_longitude = $request->d_longitude ? $request->d_longitude : "";

                            if($request->s_latitude){ $requests->s_latitude = $request->s_latitude; }
                            if($request->s_longitude) { $requests->s_longitude = $request->s_longitude; }

                            $requests->save();

                            if($requests) {
                                $requests->status = REQUEST_WAITING;

                                $request_meta = new RequestsMeta;

                                $request_meta->status = REQUEST_META_OFFERED;  // Request status change

                                // Availablity status change
                                    $provider->waiting_to_respond = WAITING_TO_RESPOND;
                                    $provider->save();


                                // Send push notifications to the first provider
                                $title = Helper::get_push_message(604);
                                $message = "You got a new request from".$user->name;

                                $this->dispatch(new sendPushNotification($request->provider_id,2,$requests->id,$title,$message,''));

                                // Push End

                                $request_meta->request_id = $requests->id;
                                $request_meta->provider_id = $request->provider_id;
                                $request_meta->save();

                                $response_array = array(
                                    'success' => true,
                                    'request_id' => $requests->id,
                                    'current_provider' => $request->provider_id,
                                    'address' => $requests->s_address,
                                    'latitude' => $requests->s_latitude,
                                    'longitude' => $requests->s_longitude,
                                );

                                $response_array = Helper::null_safe($response_array); Log::info('Create request end');
                            } else {
                                $response_array = array('success' => false , 'error' => Helper::get_error_message(126) , 'error_code' => 126 );
                            }

                        } else {
                            $response_array = array('success' => false , 'error' => Helper::get_error_message(127) , 'error_code' => 127);
                        }

                    } else {
                        $response_array = array('success' => false , 'error' => Helper::get_error_message(153) ,'error_code' => 153);
                    }
                } else {
                    $response_array = array('success' => false , 'error' => Helper::get_error_message(142) ,'error_code' => 142);
                }
            }
        }
        $response = response()->json($response_array, 200);
        return $response;
    }

    public function cancel_request(Request $request) {

        $user_id = $request->id;

        $validator = Validator::make(
            $request->all(),
            array(
                'request_id' => 'required|numeric|exists:requests,id,user_id,'.$user_id,
                'reason_id' => 'required',
            ));

        if ($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages'=>$error_messages);
        }else
        {
            $request_id = $request->request_id;
            $requests = Requests::find($request_id);
            $requestStatus = $requests->status;
            $providerStatus = $requests->provider_status;

            $allowedCancellationStatuses = array(
                PROVIDER_ACCEPTED,
                PROVIDER_STARTED,
            );
        
            $cancellation_fee = 0 ;//making fine as zero initially
            // Check whether request cancelled previously
            if($requestStatus != REQUEST_CANCELLED)
            {
                // Check whether request eligible for cancellation

                if(in_array($providerStatus, $allowedCancellationStatuses)) {

                        $reason = CancellationReasons::find($request->reason_id);
                        $cancellation_fee = $reason->cancel_fee;

                        // Update status of the request to cancellation with fine
                        $requests->status = REQUEST_CANCELLED;
                        $requests->cancellation_fine = $cancellation_fee;
                        $requests->cancel_usr_shown = 1;
                        $requests->request_cancelled = 1;
                        $requests->save();

                        // If request has confirmed provider then release him to available status
                        if($requests->confirmed_provider != DEFAULT_FALSE){

                            $provider = Provider::find( $requests->confirmed_provider );
                            $provider->is_available = PROVIDER_AVAILABLE;
                            $provider->waiting_to_respond = WAITING_TO_RESPOND_NORMAL;
                            $provider->save();

                            // Send Push Notification to Provider
                            $title = Helper::tr('cancel_by_user_title');
                            $message = Helper::tr('cancel_by_user_message');

                            $this->dispatch(new sendPushNotification($requests->confirmed_provider,2,$requests->id,$title,$message,''));

                            Log::info("Cancelled request by user");
                            // Send mail notification to the provider
                            $email_data = array();

                            $subject = Helper::tr('request_cancel_user');

                            $email_data['provider_name'] = $email_data['username'] = "";

                            if($user = User::find($requests->user_id)) {
                                $email_data['username'] = $user->first_name." ".$user->last_name;
                            }

                            if($provider = Provider::find($requests->confirmed_provider)) {
                                $email_data['provider_name'] = $provider->first_name. " " . $provider->last_name;
                            }
                            // updating cancellation fee into user's cancellation charges. this amount will deduct to next trip ends
                            $old_cancellation_charges = $user->cancellation_charges;
                            $new_cancellation_charges = $old_cancellation_charges + $cancellation_fee;
                            $user->cancellation_charges = $new_cancellation_charges;
                            $user->save();

                            $page = "emails.user.request_cancel";
                            // $email_send = Helper::send_email($page,$subject,$provider->email,$email_data);
                        }

                        // No longer need request specific rows from RequestMeta
                        RequestsMeta::where('request_id', '=', $request_id)->delete();

                        // $response_array = Helper::null_safe(array('success' => true));
                        $response_array = Helper::null_safe(array('success' => true,'request_id' => $request->id,'cancellation_fine' => $cancellation_fee, 'cancellation_message' => "you will be partially charged on your next ride"));

                } else {
                    $response_array = array( 'success' => false, 'error' => Helper::get_error_message(114), 'error_code' => 114 );
                }

            } else {
                $response_array = array( 'success' => false, 'error' => Helper::get_error_message(113), 'error_code' => 113 );
            }
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function waiting_request_cancel(Request $request) {

        $get_requests = Requests::where('user_id' , $request->id)->where('status' , REQUEST_WAITING)->get();

        if($get_requests) {
            foreach ($get_requests as $key => $requests) {
                $requests->status = REQUEST_CANCELLED;
                $requests->save();

                $requests_meta = RequestsMeta::where('request_id' , $requests->id);
                $current_provider = $requests_meta->where('status' , DEFAULT_TRUE)->first()->provider_id;
                if($provider = Provider::find($current_provider)) {
                    $provider->waiting_to_respond = WAITING_TO_RESPOND_NORMAL;
                    $provider->save();
                }

                $delete_request_meta = RequestsMeta::where('request_id' , $requests->id)->delete();

                //Send notification to the provider
                $title = Helper::tr('waiting_cancel_by_user_title');
                $message =  Helper::tr('waiting_cancel_by_user_message');

                Log::info("waiting cancelled - current provider".$current_provider);

                $this->dispatch(new sendPushNotification($current_provider,2,$requests->id,$title,$message,''));
            }
        }

        $response_array = array('success' => true);

        return response()->json($response_array , 200);

    }

    public function request_status_check(Request $request) {
        $setting = Settings::where('key','currency')->first();
        $number_tolls = "";

        $is_cancelled = 0;

        $cancel_req = Requests::where('cancel_dri_shown',1)->first();
        if($cancel_req){
            $is_cancelled = 1;
            $cancel_req->cancel_dri_shown = 2;
            $cancel_req->save();
        }

        $user = User::find($request->id);
        $cancellation_fine = $user->cancellation_charges;

        $check_status = array(REQUEST_NEW,REQUEST_COMPLETED,REQUEST_CANCELLED,REQUEST_NO_PROVIDER_AVAILABLE);

        $requests = Requests::where('requests.user_id', '=', $request->id)
                            ->whereNotIn('requests.status', $check_status)
                            ->leftJoin('users', 'users.id', '=', 'requests.user_id')
                            ->leftJoin('providers', 'providers.id', '=', 'requests.confirmed_provider')
                            ->leftJoin('service_types', 'service_types.id', '=', 'requests.request_type')
                            ->select(
                                'requests.id as request_id',
                                'requests.request_type as request_type',
                                'service_types.name as service_type_name',
                                'service_types.provider_name as service_provider_name',
                                'requests.end_time as end_time',
                                'request_start_time as request_start_time',
                                'requests.request_status_type as request_status_type',
                                'requests.airport_price_id as airport_price_id',
                                'requests.status','providers.id as provider_id',
                                'requests.is_address_changed','requests.is_adstop',
                                'requests.adstop_address','requests.adstop_latitude','requests.adstop_longitude',
                                DB::raw('CONCAT(providers.first_name, " ", providers.last_name) as provider_name'),
                                'providers.picture as provider_picture','providers.car_image','providers.model','providers.plate_no','providers.color',
                                'providers.mobile as provider_mobile', 'providers.latitude as driver_latitude',
                                'providers.longitude as driver_longitude',
                                'requests.provider_status',
                                'requests.amount',
                                DB::raw('CONCAT(users.first_name, " ", users.last_name) as user_name'),
                                'users.picture as user_picture',
                                'users.id as user_id',
                                'requests.s_latitude','requests.d_latitude',
                                'requests.s_longitude','requests.d_longitude',
                                'requests.s_address','requests.d_address',
                                'service_types.picture as type_picture'
                            )->distinct('requests.id')->orderBy('requests.later' , 'ASC')->get()->toArray();

        $requests_data = array();
        $invoice = array();

        if($requests) {
            foreach ($requests as  $req) {

                if($req['request_status_type'] == AIRPORT_PACKAGE) {
                    if($tolls = AirportPrice::where('id',$req['airport_price_id'])->first()) {
                        $number_tolls = $tolls->number_tolls;
                    }else {
                        $number_tolls = "";
                    }
                }else {
                    $number_tolls = "";
                }


                $req['rating'] = DB::table('user_ratings')->where('provider_id', $req['provider_id'])->avg('rating') ?: 0;

                // unset($req['provider_id']);
                $requests_data[] = $req;

                $allowed_status = array(REQUEST_COMPLETE_PENDING,REQUEST_COMPLETED,REQUEST_RATING,WAITING_FOR_PROVIDER_CONFRIMATION_COD);

                if( in_array($req['status'], $allowed_status)) {

                    $invoice_query = RequestPayment::where('request_id' , $req['request_id'])
                                    ->leftJoin('requests' , 'request_payments.request_id' , '=' , 'requests.id')
                                    ->leftJoin('users' , 'requests.user_id' , '=' , 'users.id')
                                    ->leftJoin('cards' , 'users.default_card' , '=' , 'cards.id');
                    if($user->payment_mode == CARD) {
                        $invoice_query = $invoice_query->where('cards.is_default' , DEFAULT_TRUE) ;
                    }

                    $invoice = $invoice_query->select('requests.confirmed_provider as provider_id' , 'request_payments.total_time',
                                        'request_payments.payment_mode as payment_mode' , 'request_payments.base_price',
                                        'request_payments.time_price' ,'request_payments.distance_travel','request_payments.distance_unit',
                                        'request_payments.distance_price','request_payments.tax_price' , 'request_payments.total',
                                        'cards.card_token','cards.customer_id','cards.last_four')
                                    ->get()->toArray();
                }
            }
        }

        $data = Helper::null_safe($requests_data);
        $invoice = Helper::null_safe($invoice);
        
        if(!empty($invoice)) {
            \Log::info($invoice);
            $marketPrice = Tron::marketPrice();            
            $invoice[0]['total_equv_usd'] = number_format($marketPrice * $invoice[0]['total'], 2, '.', '');
        }

        $response_array = array(
            'success' => true,
            'is_cancelled' => $is_cancelled,
            'currency' => $setting->value,
            'data' => $data,
            'cancellation_fine' => $cancellation_fine,
            'invoice' => $invoice,
            'number_tolls' => $number_tolls
        );

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function paybypaypal(Request $request) {

        $user = User::find($request->id);

        $validator = Validator::make($request->all() ,
            array(

                'request_id' => 'required|exists:requests,id,user_id,'.$request->id,
                'payment_mode' => 'required|in:'.PAYPAL.'|exists:settings,key,value,1',
                'is_paid' => 'required|in:'.DEFAULT_TRUE,
                'payment_id' => 'required',
            ),array(
                'exists' => 'The :attribute doesn\'t belong to user:'.$user->firstname.' '.$user->last_name,
                'in'      => 'The :attribute must be one of the following types: :values',
            )
            );
        if($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);

        } else {
            $requests = Requests::where('id',$request->request_id)->where('status' , REQUEST_COMPLETE_PENDING)->first();
            // Check the status is completed
            if( $requests && $requests->status != REQUEST_RATING) {

                if($requests->status == REQUEST_COMPLETE_PENDING) {

                    $requests->status = REQUEST_RATING;

                    $requests->is_paid = DEFAULT_TRUE;
                    $requests->amount = $request->amount;
                    $requests->save();

                    if($request_payment = RequestPayment::where('request_id' , $request->request_id)->first()) {

                        $request_payment->payment_id = $request->payment_id;
                        $request_payment->payment_mode = $request->payment_mode;
                        $request_payment->status = DEFAULT_TRUE;
                        $request_payment->save();
                    }

                    // Send push notification to provider

                    if($user)
                        $title =  "The"." ".$user->first_name.' '.$user->last_name." done the payment";
                    else
                        $title = Helper::tr('request_completed_user_title');

                    $message = Helper::tr('request_completed_user_message');
                    $this->dispatch(new sendPushNotification($requests->confirmed_provider,2,$requests->id,$title,$message,''));

                     // Send mail notification to the provider
                    $subject = Helper::tr('request_completed_bill');
                    $email = Helper::get_emails(3,$request->id,$requests->confirmed_provider);
                    $page = "emails.user.invoice";
                    Helper::send_invoice($requests->id,$page,$subject,$email);

                    // Send Response
                    $response_array =  Helper::null_safe(array('success' => true , 'message' => Helper::get_message(107)));

                } else {
                    $response_array = array('success' => 'false' , 'error' => Helper::get_error_message(137) , 'error_code' => 137);
                }

            } else {
                $response_array = array('success' => 'false' , 'error' => Helper::get_error_message(138) , 'error_code' => 138);
            }
        }

        return response()->json($response_array,200);

    }

    public function paynow(Request $request) {
        $response_array = array('success' => true);
        return response()->json($response_array , 200);
    }

    public function paynow_old(Request $request) {

        $validator = Validator::make($request->all(),
            array(
                    'request_id' => 'required|exists:requests,id,user_id,'.$request->id,
                    'payment_mode' => 'required|in:'.COD.','.PAYPAL.','.CARD.','.WALLETBAY.'|exists:settings,key,value,1',
                    'is_paid' => 'required',
                ),
            array(
                    'exists' => Helper::get_error_message(139),
                )
            );

        if($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false , 'error' => $error_messages , 'error_messages' => Helper::get_error_message(101));
        } else {

            $requests = Requests::where('id',$request->request_id)->where('status' , REQUEST_COMPLETE_PENDING)->first();
            $user = User::find($request->id);

            //Check current status of the request
            if($requests && intval($requests->status) != REQUEST_RATING ) {

                $total = 0;

                if($request_payment = RequestPayment::where('request_id' , $request->request_id)->first()) {
                    $request_payment->payment_mode = $request->payment_mode;
                    $request_payment->save();
                    $total = $request_payment->total;
                }

                if($request->payment_mode == COD) {

                    $requests->status = WAITING_FOR_PROVIDER_CONFRIMATION_COD;
                    // $requests->is_paid = DEFAULT_TRUE;

                    $request_payment->payment_id = uniqid();
                    $request_payment->status = DEFAULT_TRUE;

                    $user->cancellation_charges = 0; //updating cancellation charges 
                    $user->save();

                } elseif($request->payment_mode == CARD || $request->payment_mode == PAYPAL) {

                    $check_card_exists = User::where('users.id' , $request->id)
                                ->leftJoin('cards' , 'users.id','=','cards.user_id')
                                ->where('cards.id' , $user->default_card)
                                ->where('cards.is_default' , DEFAULT_TRUE);

                    if($check_card_exists->count() != 0) {

                        $user_card = $check_card_exists->first();

                      if($total != 0){
                            //BRAINTREE PAYMENT
                          $transaction = Helper::createTransaction($user_card->customer_id,$requests->id,$total);

                          if($transaction == '0'){
                            $response_array = array('success' => false, 'error' => Helper::get_error_message(158) , 'error_code' => 158);
                            return response()->json($response_array , 200);
                          }
                          else {
                              $request_payment->status = DEFAULT_TRUE;
                              $request_payment->payment_id = $transaction;
                              $requests->is_paid = DEFAULT_TRUE;
                              $requests->status = REQUEST_RATING;
                              $requests->amount = $total;

                                $user->cancellation_charges = 0;
                                $user->save();
                          }
                      }else {
                          $response_array = array('success' => false, 'error' => Helper::get_error_message(158) , 'error_code' => 159);
                          return response()->json($response_array , 200);
                      }

                    } else {
                        $response_array = array('success' => false, 'error' => Helper::get_error_message(140) , 'error_code' => 140);
                        return response()->json($response_array , 200);
                    }

                }elseif($request->payment_mode == WALLETBAY){

                    $settings = Settings::where('key', 'wallet_bay_key')->first();
                    $wallet_bay_key = $settings->value;

                    $wallet_url = Settings::where('key', 'wallet_url')->first();
                    $wallet_url = $wallet_url->value;

                    $headers = array('Authorization:'.$wallet_bay_key);

                    $ch = curl_init($wallet_url.'/api/businesses/users/'.$user->id.'/balance');

                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

                    // execute!
                    $response = curl_exec($ch);

                    // close the connection, release resources used
                    curl_close($ch);

                    $data = \GuzzleHttp\json_decode($response);

                    $walletTotal = $data->data->user_balance;

                    $pendingAmount = 0;
                    $referenceId = uniqid();

                    if($walletTotal < $total){
                        $pendingAmount = $total - $walletTotal;

                        $post = array("amount"=>$walletTotal,"reference_id"=>$referenceId);
                    }else{

                        $post = array("amount"=>$total,"reference_id"=>$referenceId);
                    }



                    //DEBIT THE AMOUNT FROM WALLET
                    $wallet_url = Settings::where('key', 'wallet_url')->first();
                    $wallet_url = $wallet_url->value;

                    $ch = curl_init($wallet_url.'/api/businesses/users/'.$user->id.'/balance/debit');

                    // $ch = curl_init('http://165.227.126.172/api/businesses/users/'.$user->id.'/balance/debit');
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

                    // execute!
                    $response = curl_exec($ch);

                    // close the connection, release resources used
                    curl_close($ch);


                    $request_payment->status = DEFAULT_TRUE;
                    $request_payment->payment_id = $referenceId;
                    $requests->is_paid = DEFAULT_TRUE;
                    $requests->status = REQUEST_RATING;
                    $requests->amount = $total;

                    $user->cancellation_charges = 0;
                    $user->save();
                }

                $requests->save();
                $request_payment->save();

                // Send notification to the provider Start
                if($user)
                    $title =  "The"." ".$user->first_name.' '.$user->last_name." done the payment";
                else
                    $title = Helper::tr('request_completed_user_title');

                $message = Helper::get_push_message(603);
                $this->dispatch(new sendPushNotification($requests->confirmed_provider,2,$requests->id,$title,$message,''));
                // Send notification end

                // Send invoice notification to the user, provider and admin
                $subject = Helper::tr('request_completed_bill');
                $email = Helper::get_emails(3,$request->id,$requests->confirmed_provider);
                $page = "emails.user.invoice";
                //Helper::send_invoice($requests->id,$page,$subject,$email);


                $response_array = array('success' => true);


                //CHECKING WALLETBAY

                if($request->payment_mode == WALLETBAY){
                    if($pendingAmount != 0){
                        $response_array = array('success' => false,'error' => 'Please pay the remaining amount '.$pendingAmount.' to the driver' , 'error_code' => 164);
                    }
                }

            } else {
                $response_array = array('success' => false,'error' => Helper::get_error_message(138) , 'error_code' => 138);
            }
        }

        return response()->json($response_array , 200);

    }

     public function rate_provider(Request $request) {

        $user = User::find($request->id);

        $validator = Validator::make(
            $request->all(),
            array(
                'request_id' => 'required|integer|exists:requests,id,user_id,'.$user->id.'|unique:user_ratings,request_id',
                'rating' => 'required|integer|in:'.RATINGS,
                'comments' => 'max:255',
                'is_favorite' => 'in:'.DEFAULT_TRUE.','.DEFAULT_FALSE,
            ),
            array(
                'exists' => 'The :attribute doesn\'t belong to user:'.$user->id,
                'unique' => 'The :attribute already rated.'
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages'=>$error_messages);

        } else {

            $request_id = $request->request_id;
            $comment = $request->comment;

            $req = Requests::where('id' ,$request_id)
                    ->where('status' ,REQUEST_RATING)
                    ->first();

            if ($req && intval($req->status) != REQUEST_COMPLETED) {
                //Save Rating
                $rev_user = new UserRating();
                $rev_user->provider_id = $req->confirmed_provider;
                $rev_user->user_id = $req->user_id;
                $rev_user->request_id = $req->id;
                $rev_user->rating = $request->rating;
                $rev_user->comment = $comment ? $comment: '';
                $rev_user->save();

                $provider = Provider::find($req->confirmed_provider);
                $provider->is_available = DEFAULT_TRUE;
                $provider->save();

                $req->status = REQUEST_COMPLETED;
                $req->save();


                // Send Push Notification to Provider
                // $title = Helper::tr('provider_rated_by_user_title');
                // $messages = Helper::tr('provider_rated_by_user_message');
                // $this->dispatch( new sendPushNotification($req->confirmed_provider, PROVIDER,$req->id,$title, $messages));
                $user = User::find($request->id);
                $referee_bonus = $user->referee_bonus;

                $response_array = array('success' => "true", "referee_bonus" => $referee_bonus);

            } else {
                $response_array = array('success' => false,'error' => Helper::get_error_message(150),'error_code' => 150);
            }
        }

        $response = response()->json($response_array, 200);
        return $response;
    }

    public function history(Request $request) {
        $setting = Settings::where('key','currency')->first();
        // Get the completed request details
        $requests = Requests::where('requests.user_id', '=', $request->id)
                            ->where('requests.status', '=', REQUEST_COMPLETED)
                            ->leftJoin('providers', 'providers.id', '=', 'requests.confirmed_provider')
                            ->leftJoin('users', 'users.id', '=', 'requests.user_id')
                            ->leftJoin('cards' , 'users.default_card' , '=' , 'cards.id')
                            ->leftJoin('service_types','service_types.id','=','requests.request_type')
                            ->leftJoin('request_payments', 'requests.id', '=', 'request_payments.request_id')
                            ->orderBy('request_start_time','desc')
                            ->select('requests.id as request_id','requests.s_latitude','requests.s_longitude','requests.d_latitude','requests.d_longitude','service_types.name as taxi_name',
                            'requests.s_address as s_address','requests.d_address as d_address', 'requests.request_type as request_type', 'request_start_time as date',
                                    DB::raw('CONCAT(providers.first_name, " ", providers.last_name) as provider_name'), 'providers.picture',
                                    DB::raw('ROUND(request_payments.total) as total'),
                                    'requests.confirmed_provider as provider_id' , 'request_payments.total_time',
                                        'request_payments.payment_mode as payment_mode' , 'request_payments.base_price', 'request_payments.min_fare',
                                        'request_payments.time_price' ,'request_payments.distance_travel','request_payments.distance_unit',
                                        'request_payments.distance_price','request_payments.tax_price' ,'request_payments.booking_fee' , 'request_payments.total',
                                        'cards.card_token','cards.customer_id','cards.last_four','users.referee_bonus','request_payments.promo_value'
                                    )
                                    ->get();

        $request_details = array();
        $user = User::find($request->id);
        $userTimezone = $user->timezone;
        foreach($requests as $req){
            $request_det = array();
            $request_det['request_id'] = $req->request_id;
            $request_det['taxi_name'] = $req->taxi_name;
            $request_det['s_address'] = $req->s_address;
            $request_det['d_address'] = $req->d_address;
            $request_det['date'] = Helper::convertTimeToUSERzone($req->date, $userTimezone, $format = 'Y-m-d H:i:s');
            $request_det['provider_name'] = $req->provider_name;
            $request_det['picture'] = $req->picture;
            $request_det['total'] = $req->total;

            // $key = env('GOOGLE_MAP_KEY_FOR_RIDEY');
            $key = Helper::getKey();
            $gStaticMapBaseUrl = 'http://maps.googleapis.com/maps/api/staticmap';
            $gStaticMapInputs = array();
            $gStaticMapInputs[] = 'key='.$key;
            $gStaticMapInputs[] = 'autoscale=false';
            $gStaticMapInputs[] = 'size=570x340';
            $gStaticMapInputs[] = 'maptype=roadmap';
            $gStaticMapInputs[] = 'format=png';
            //$gStaticMapInputs[] = 'visual_refresh=true';
            $gStaticMapInputs[] = 'markers=size:mid|color:0x0bc720|label:A|' . $req->s_latitude . ',' . $req->s_longitude;
            $gStaticMapInputs[] = 'markers=size:mid|color:0xff0000|label:B|' . $req->d_latitude . ',' . $req->d_longitude;
            $gStaticMapInputs['path'] = 'path=weight:5|color:blue';
            $gMapDirectionsJsonString = file_get_contents('http://maps.googleapis.com/maps/api/directions/json?avoid=tolls|highways&origin=' . $req->s_latitude . ',' . $req->s_longitude . '&destination=' . $req->d_latitude . ',' . $req->d_longitude);
            if($gMapDirectionsJsonString){
                $gMapDirectionsJsonObject = json_decode($gMapDirectionsJsonString);
                if($gMapDirectionsJsonObject->status == 'OK'){
                    $gStaticMapInputs['path'] = 'path=weight:5|color:blue|enc:' . $gMapDirectionsJsonObject->routes[0]->overview_polyline->points;
                }
            }
            $gStaticMapInputsString = implode('&', $gStaticMapInputs);
            $request_det['map_image'] =  $gStaticMapBaseUrl . '?' . $gStaticMapInputsString;
            $distance_travel = $req->distance_travel;
            $request_det['provider_id'] =  $req->provider_id;
            $request_det['total_time'] =  $req->total_time;
            $request_det['payment_mode'] =  $req->payment_mode;
            $request_det['min_price'] =  $req->min_fare;
            $request_det['base_price'] =  $req->base_price;
            $request_det['time_price'] =  $req->time_price;
            $request_det['distance_unit'] =  $req->distance_unit;
            $request_det['distance_travel'] =  round($distance_travel, 3);
            $request_det['distance_price'] =  $req->distance_price;
            $request_det['tax_price'] =  $req->tax_price;
            $request_det['booking_fee'] =  $req->booking_fee;
            $request_det['total'] =  $req->total;
            $request_det['card_token'] =  $req->card_token;
            $request_det['customer_id'] =  $req->customer_id;
            $request_det['last_four'] =  $req->last_four;
            $request_det['currency'] =  $setting->value;
            array_push($request_details, $request_det);
        }
        $request_details = Helper::null_safe($request_details);

        $response_array = array('success' => true,'requests' => $request_details);

        return response()->json($response_array , 200);
    }

    public function single_request(Request $request) {

        $user = User::find($request->id);

        $validator = Validator::make(
            $request->all(),
            array(
                'request_id' => 'required|integer|exists:requests,id,user_id,'.$user->id,
            ),
            array(
                'exists' => 'The :attribute doesn\'t belong to user:'.$user->id,
            )
        );

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages'=>$error_messages);

        } else {

            $requests = Requests::where('requests.id' , $request->request_id)
                                ->leftJoin('providers' , 'requests.confirmed_provider','=' , 'providers.id')
                                ->leftJoin('users' , 'requests.user_id','=' , 'users.id')
                                ->leftJoin('user_ratings' , 'requests.id','=' , 'user_ratings.request_id')
                                ->leftJoin('request_payments' , 'requests.id','=' , 'request_payments.request_id')
                                ->leftJoin('service_types', 'service_types.id', '=', 'requests.request_type')
                                ->leftJoin('cards','users.default_card','=' , 'cards.id')
                                ->select('providers.id as provider_id' , 'providers.picture as provider_picture','request_payments.payment_mode as payment_mode',
                                    DB::raw('CONCAT(providers.first_name, " ", providers.last_name) as provider_name'),'user_ratings.rating','user_ratings.comment',
                                     DB::raw('ROUND(request_payments.base_price) as base_price'), DB::raw('ROUND(request_payments.tax_price) as tax_price'),
                                     DB::raw('ROUND(request_payments.time_price) as time_price'), DB::raw('ROUND(request_payments.total) as total'),
                                    'cards.id as card_id','cards.customer_id as customer_id',
                                    'cards.card_token','cards.last_four',
                                    'requests.id as request_id','requests.before_image','requests.after_image',
                                    'requests.user_id as user_id',
                                    'requests.request_type as request_type',
                                    'service_types.name as service_type_name',
                                    'service_types.provider_name as service_provider_name',
                                    DB::raw('CONCAT(users.first_name, " ", users.last_name) as user_name'))
                                ->get()->toArray();

            $response_array = Helper::null_safe(array('success' => true , 'data' => $requests));
        }

        return response()->json($response_array , 200);

    }

    public function get_payment_modes(Request $request) {

        $payment_modes = array();
        //'cod','paypal','card','walletbay', 'tron_wallet'
        $modes = Settings::whereIn('key' , array('tron_wallet'))->where('value' , 1)->get();
        if($modes) {
            foreach ($modes as $key => $mode) {
                $payment_modes[$key] = $mode->key;
            }
        }

        $response_array = Helper::null_safe(array('success' => true , 'payment_modes' => $payment_modes));

        return response()->json($response_array,200);
    }

    public function get_user_payment_modes(Request $request) {

        $user = User::find($request->id);

        if($user->payment_mode) {

            $payment_data = $data = $card_data = array();

            if($user_cards = Cards::where('user_id' , $request->id)->get()) {
                foreach ($user_cards as $c => $card) {
                    $data['id'] = $card->id;
                    $data['customer_id'] = $card->customer_id;
                    $data['card_id'] = $card->card_token;
                    $data['last_four'] = $card->last_four;
                    $data['is_default']= $card->is_default;

                    array_push($card_data, $data);
                }
            }

            $response_array = Helper::null_safe(array('success' => true, 'payment_mode' => $user->payment_mode , 'card' => $card_data));

        } else {
            $response_array = array('success' => false , 'error' => Helper::get_error_message(130) , 'error_code' => 130);
        }
        return response()->json($response_array , 200);

    }

    public function payment_mode_update(Request $request) {

        $validator = Validator::make($request->all() ,
            array(
                'payment_mode' => 'required|in:'.COD.','.PAYPAL.','.CARD.','.WALLETBAY.',tron_wallet',
                )
            );
         if($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false ,'error' => $error_messages , 'error_messages' => Helper::get_error_message(101));
        } else {
            $user = User::where('id', '=', $request->id)->update( array('payment_mode' => $request->payment_mode));

            $response_array = Helper::null_safe(array('success' => true , 'message' => Helper::get_message(109)));
        }

        return response()->json($response_array , 200);

    }

    public function add_card(Request $request) {

        $validator = Validator::make(
                    $request->all(),
                    array(
                        'last_four' => 'required',
                        'card_token' => 'required',
                        'customer_id' => 'required',
                    )
                );

        if ($validator->fails())
        {
           $error_messages = implode(',', $validator->messages()->all());
           $response_array = array('success' => false , 'error' => Helper::get_error_message(101) , 'error_code' => 101 , 'error_messages' => $error_messages);

        } else {

            $user = User::find($request->id);

            $customer_id = $request->customer_id;

            $cards = new Cards;
            $cards->user_id = $request->id;
            $cards->customer_id = $request->customer_id;
            $cards->last_four = $request->last_four;
            $cards->card_token = $request->card_token;

            // Check is any default is available
            $check_card = Cards::where('user_id',$request->id)->first();

            if($check_card ) {
                $cards->is_default = 0;
            } else {
                $cards->is_default = 1;
            }

            $cards->save();

            if($user) {
                // $user->payment_mode = CARD;
                $user->default_card = $check_card ? $user->default_card : $cards->id;
                $user->save();
            }

            $response_array = Helper::null_safe(array('success' => true));
        }

        $response = response()->json($response_array,200);
        return $response;
    }

    public function delete_card(Request $request) {

        $card_id = $request->card_id;

        $validator = Validator::make(
            $request->all(),
            array(
                'card_id' => 'required|integer|exists:cards,id,user_id,'.$request->id,
            ),
            array(
                'exists' => 'The :attribute doesn\'t belong to user:'.$request->id
            )
        );

        if ($validator->fails()) {
           $error_messages = implode(',', $validator->messages()->all());
           $response_array = array('success' => false , 'error' => Helper::get_error_message(101) , 'error_code' => 101 , 'error_messages' => $error_messages);
        } else {

            Cards::where('id',$card_id)->delete();

            $user = User::find($request->id);

            if($user) {

                if($user->payment_mode = CARD) {
                    // Check he added any other card
                    if($check_card = Cards::where('user_id' , $request->id)->first()) {
                        $check_card->is_default =  DEFAULT_TRUE;
                        $user->default_card = $check_card->id;
                        $check_card->save();
                    } else {
                        $user->payment_mode = COD;
                        $user->default_card = DEFAULT_FALSE;
                    }
                }

                $user->save();
            }

            $response_array = array('success' => true );
        }

        return response()->json($response_array , 200);
    }

    public function default_card(Request $request) {

        $validator = Validator::make(
            $request->all(),
            array(
                'card_id' => 'required|integer|exists:cards,id,user_id,'.$request->id,
            ),
            array(
                'exists' => 'The :attribute doesn\'t belong to user:'.$request->id
            )
        );

        if($validator->fails()) {

            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages'=>$error_messages);

        } else {

            $user = User::find($request->id);

            $old_default = Cards::where('user_id' , $request->id)->where('is_default', DEFAULT_TRUE)->update(array('is_default' => DEFAULT_FALSE));

            $card = Cards::where('id' , $request->card_id)->update(array('is_default' => DEFAULT_TRUE));

            if($card) {
                if($user) {
                    // $user->payment_mode = CARD;
                    $user->default_card = $request->card_id;
                    $user->save();
                }
                $response_array = Helper::null_safe(array('success' => true));
            } else {
                $response_array = array('success' => false , 'error' => 'Something went wrong');
            }
        }
        return response()->json($response_array , 200);

    }

    public function message_get(Request $request)
    {
        $Messages = ChatMessage::where('request_id', $request->request_id)
                ->where('user_id', $request->id)
                ->take(1000)
                ->get();

        $response_array = [
            'success' => true,
            'data' => $Messages->toArray(),
        ];

        return response()->json($response_array, 200);
    }


    public function getBraintreeToken(Request $request)
    {
        $clientToken = Braintree_ClientToken::generate();
        $response_array = array(
                'success' => true,
                'client_token' => $clientToken
        );
        $response = response()->json($response_array, 200);
        return $response;

    }


    public function userAddCard(Request $request)
    {

        $payment_method_nonce = $request->payment_method_nonce;
        $user = User::find($request->id);
        $payment = Cards::where('user_id',$request->id)->where('is_deleted',0)->first();


        try{

            if(!$payment){

                $result = Braintree_Customer::create(array(
                    'firstName' => $user->first_name,
                    'lastName' => $user->last_name,
                    'paymentMethodNonce' => $payment_method_nonce
                 ));
                //dd($result->customer->creditCards[0]->cardType);

                if($result->success){

                    Log::info('New User creation success');
                    if($result->customer->creditCards){

                        $payment = new Cards;
                        $payment->user_id = $request->id;
                        $payment->customer_id = $result->customer->id;
                        $payment->payment_method_nonce = $request->payment_method_nonce;
                        $payment->last_four = (string)$result->customer->creditCards[0]->last4;
                        $payment->is_default=1;
                        $payment->card_type = $result->customer->creditCards[0]->cardType;
                        $payment->card_token = 'na';
                        $payment->save();

                        Log::info("First card");

                        if($user) {
                            // $user->payment_mode = CARD;

                            $user->default_card = $payment->id;
                            $user->save();
                            Log::info('Default card changed');
                        }

                        $response_array = array('success' => true,'message'=>'Thank you for adding your first card.');


                    }elseif($result->customer->paypalAccounts){
                        //adding paypal
                        $payment = new Cards;
                        $payment->user_id = $request->id;
                        $payment->customer_id = $result->customer->id;
                        $payment->payment_method_nonce = $request->payment_method_nonce;
                        $payment->paypal_email = $result->customer->paypalAccounts[0]->email;
                        $payment->card_type = 'na';
                        $payment->card_token = 'na';
                        $payment->save();
                        $response_array = array('success' => true,'message'=>'Thank you for adding your first paypal account.');
                    }

                }else{// $result->success failed

                    $response_array = array('success' => true,'message'=>'Braintree Adding Card Error: '.$result->message);

                }


            }else{ //if payment exist in payment table

                $customer_id = $payment->customer_id;
                $result = Braintree_PaymentMethod::create(array(
                    'customerId' => $customer_id,
                    'paymentMethodNonce' => $payment_method_nonce
                ));
                //dd($result);
                if($result->success)  {

                if(preg_match('/Braintree_CreditCard/', $result->paymentMethod)){
                    //credit card

                    $payment = new Cards;
                    $payment->user_id = $request->id;
                    $payment->customer_id = $customer_id;
                    $payment->payment_method_nonce = $request->payment_method_nonce;
                    $payment->last_four = (string)$result->paymentMethod->last4;
                    $payment->card_type = $result->paymentMethod->cardType;
                    $payment->card_token = 'na';
                    $payment->save();

                    Log::info("Second card");

                    $response_array = array('success' => true,'message'=>'Thank you for adding your card.');


                }elseif(preg_match('/Braintree_PayPalAccount/', $result->paymentMethod)
                    ){
                    //paypal
                    $payment = new Cards;
                    $payment->user_id = $request->id;
                    $payment->customer_id = $customer_id;
                    $payment->payment_method_nonce = $request->payment_method_nonce;
                    $payment->paypal_email = $result->paymentMethod->email;
                    $payment->card_type = 'na';
                    $payment->card_token = 'na';
                    $payment->save();
                    $response_array = array('success' => true,'message'=>'Thank you for adding your paypal account.');
                }
            }else{
                // failed

                $response_array = array('success' => true,'message'=>'Braintree Adding Card Error: '.$result->message);
            }
            }

        }catch(Braintree_Exception_Authorization $e){
            Log::error('Error = '.$e->getMessage());
            $response_array = array('success' => true,'message'=>'Something went wrong. Please try again later or contact us.');
        }

        $response = response()->json($response_array, 200);
        return $response;
    }


    public function selectCard(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            array(
                'card_id' => 'required|integer|exists:cards,id,user_id,'.$request->id,
                'payment_mode' => 'required|in:'.COD.','.PAYPAL.','.CARD.'|exists:settings,key,value,1',
            ),
            array(
                    'exists' => Helper::get_error_message(139),
                ));

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error_message' =>$error_messages , 'error_code' => 101, 'error_messages'=>$error_messages);

        } else {

            if($request->payment_mode == CARD){

                $user = User::find($request->id);

                $old_default = Cards::where('user_id' , $request->id)->where('is_default', DEFAULT_TRUE)->update(array('is_default' => DEFAULT_FALSE));

                $card = Cards::where('id' , $request->card_id)->update(array('is_default' => DEFAULT_TRUE));

                if($card) {
                    if($user) {
                        // $user->payment_mode = CARD;
                        $user->default_card = $request->card_id;
                        $user->save();
                    }
                    $response_array = Helper::null_safe(array('success' => true));
                } else {
                    $response_array = array('success' => false , 'error' => 'Something went wrong');
                }

                Log::info("default card changed");
            }else{
                Log::info("payment_mode is different".print_r($request->payment_mode,true));

                $response_array = array(
                'success' => true
                );
            }

        }
        $response = response()->json($response_array, 200);
        return $response;

    }

    public function getCards(Request $request)
    {

        //get user tron wallet
        $marketPrice = Tron::marketPrice();
        $balance = Tron::getUserBalance($request->id);
        $wallet = Tron::getUserWallet($request->id);
     

        $payment = Cards::where('user_id',$request->id)->first();
        $cardArray = array();
        if($payment){

            $payment_data = Cards::where('user_id',$request->id)
                            ->where('is_deleted',0)
                            ->get();

            foreach($payment_data as $pay){
                $card['id'] = $pay->id;
                $card['customer_id'] = $pay->customer_id;
                if($pay->last_four){
                    $card['last_four'] = $pay->last_four;
                    $card['type'] = 'card';
                    $card['card_type'] = $pay->card_type;
                    $card['email'] = '';

                }else{
                    $card['last_four'] = '0';
                    $card['type'] = 'paypal';
                    $card['email'] = $pay->paypal_email;
                    $card['card_type'] = $pay->card_type;
                }

                $card['is_default'] = $pay->is_default;
                array_push($cardArray, $card);
            }


            $response_array = array(
                'success' => true ,
                'cards' => $cardArray,
                'balance' => $balance / pow(10, 6),
                'tron_wallet' => $wallet,
                'market_price' => number_format($marketPrice, 6, '.', '')
            );
        }else{
            //no payments
            $response_array = array(
                'success' => false ,
                'error_message' => 'No Card Found',
                'balance' => $balance / pow(10, 6),
                'tron_wallet' => $wallet,
                'market_price' => number_format($marketPrice, 6, '.', '')
            );
        }

        $response = response()->json($response_array, 200);
        return $response;

    }

    public function deleteCard(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            array(
                'card_id' => 'required',
            ));

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error_message' =>$error_messages , 'error_code' => 101, 'error_messages'=>$error_messages);

        } else {

            $payment = Cards::find($request->card_id);
            if($payment){
                if($payment->is_default == 1){
                    $response_array = array(
                    'success' => false,
                    'message' => 'Cant able to delete default card. Please change default card and try again');
                }else{
                    $payment->is_deleted = 1;
                    $payment->save();
                    
                    $response_array = array(
                    'success' => true,
                    'message' => 'Card deleted succesfully');
                }
            }else{
                $response_array = array(
                'success' => false,
                'error_message' => 'wrong card id');

            }

        }
        $response = response()->json($response_array, 200);
        return $response;
    }

    public function testPayment(Request $request)
    {
        $payment= Cards::where('user_id',$request->id)->first();

        $trans = Helper::createTransaction($payment->customer_id,30,10);
        dd($trans);
    }

    public function validate_promo(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'promo_code' => 'required',
            ));

        if ($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error_message' =>$error_messages , 'error_code' => 101, 'error_messages'=>$error_messages);

        } 
        else 
        {
            $current = date('Y-m-d h:i:s');
            $promo_code = trim($request->promo_code," ");
            Log::info('Promo Code is '.$promo_code);
            $check_total_usage = RequestPayment::where('promo_code' , $promo_code)
                                 ->get();

            $check_already_used_pc = Requests::where('requests.user_id', $request->id)
                        ->where('request_payments.promo_code' , $promo_code)
                        ->leftJoin('request_payments', 'request_payments.request_id', '=', 'requests.id')
                        ->get();

            $promo = PromoCode::where('coupon_code',$promo_code)->first();

            if($promo != NULL){

                if(count($check_total_usage) < $promo->max_promo){

                if(count($check_already_used_pc) < $promo->max_usage){
//dd(count($check_already_used_pc));
                        //$getPromo = PromoCode::where('coupon_code', $promo_code)->whereRaw('start > "'.$current.'" AND "'.$current.'" <= end')->first();
                        $getPromo = PromoCode::where('coupon_code', $promo_code)->whereRaw('"'.$current.'" between `start` and `end`')->first();
                        Log::info('Check whether code is valid '.count($getPromo));

                        if($getPromo) {
                            $amount = $getPromo->value;
                            $response_array = array('success' => true, 'amount' => $amount, 'error_message' => 'Valid Promo Code');
                        }
                        else {
                            $response_array = array('success' => false, 'error_message' => 'Invalid Promo Code');
                        }
                }else{
                    $response_array = array('success' => false, 'error_message' => 'You have exceeded the usage');
                }

              }else{
                  $response_array = array('success' => false, 'error_message' => 'Promo code expired');
              }

            }else{
                $response_array = array('success' => false, 'error_message' => 'Invalid Promo Code');
            }

            


            // if(count($check_already_used_pc) == DEFAULT_FALSE)
            // {
            //      //$getPromo = PromoCode::where('coupon_code', $promo_code)->whereRaw('"'.$current.'" between `start` and `end`')->first();
            //     $getPromo = PromoCode::where('coupon_code', $promo_code)->whereRaw('start > "'.$current.'" AND "'.$current.'" <= end')->first();
            //     Log::info('Check whether code is valid '.count($getPromo));
            //     if($getPromo) {
            //         $amount = $getPromo->value;
            //         $response_array = array('success' => true, 'amount' => $amount, 'error_message' => 'Valid Promo Code');
            //     }
            //     else {
            //         $response_array = array('success' => false, 'error_message' => 'Invalid Promo Code');
            //     }
            // }
            // else
            // {
            //     $response_array = array('success' => false, 'error_message' => 'You have already used this promo code.');
            // }
            $response = response()->json($response_array, 200);
            return $response;
        }
    }

    public function test_invoice(Request $request)
    {
        $request_id = $request->request_id;
        $requests = Requests::where('id', '=', $request_id)->first();
        $invoice_data = array();

        // $key = env('GOOGLE_MAP_KEY_FOR_RIDEY');
        $key = Helper::getKey();
        $gStaticMapBaseUrl = 'http://maps.googleapis.com/maps/api/staticmap';
        $gStaticMapInputs = array();
        $gStaticMapInputs[] = 'key='.$key;
        $gStaticMapInputs[] = 'autoscale=false';
        $gStaticMapInputs[] = 'size=570x340';
        $gStaticMapInputs[] = 'maptype=roadmap';
        $gStaticMapInputs[] = 'format=png';
        //$gStaticMapInputs[] = 'visual_refresh=true';
        $gStaticMapInputs[] = 'markers=size:mid|color:0x0bc720|label:A|' . $requests->s_latitude . ',' . $requests->s_longitude;
        $gStaticMapInputs[] = 'markers=size:mid|color:0xff0000|label:B|' . $requests->d_latitude . ',' . $requests->d_longitude;
        $gStaticMapInputs['path'] = 'path=weight:5|color:blue';
        $gMapDirectionsJsonString = file_get_contents('http://maps.googleapis.com/maps/api/directions/json?avoid=tolls|highways&origin=' . $requests->s_latitude . ',' . $requests->s_longitude . '&destination=' . $requests->d_latitude . ',' . $requests->d_longitude);
        if($gMapDirectionsJsonString){
            $gMapDirectionsJsonObject = json_decode($gMapDirectionsJsonString);
            if($gMapDirectionsJsonObject->status == 'OK'){
                $gStaticMapInputs['path'] = 'path=weight:5|color:blue|enc:' . $gMapDirectionsJsonObject->routes[0]->overview_polyline->points;
            }
        }
        $gStaticMapInputsString = implode('&', $gStaticMapInputs);
        $invoice_data['map_image'] =  $gStaticMapBaseUrl . '?' . $gStaticMapInputsString;
        $user = User::find($requests->user_id);
                $provider = Provider::find($requests->confirmed_provider);

                $invoice_data['request_id'] = $requests->id;
                $invoice_data['user_id'] = $requests->user_id;
                $invoice_data['provider_id'] = $requests->confirmed_provider;
                $invoice_data['provider_name'] = $provider->first_name." ".$provider->last_name;
                $invoice_data['provider_address'] = $provider->address;
                $invoice_data['user_name'] = $user->first_name." ".$user->last_name;
                $invoice_data['user_address'] = $requests->s_address;
          $invoice_data['d_latitude'] = $requests->d_latitude;
          $invoice_data['d_longitude'] = $requests->d_longitude;
          $invoice_data['picture'] = $user->picture;
          if($service_type_details = ServiceType::find($requests->request_type)){
            $invoice_data['type_picture'] = $service_type_details->picture;
          }else {
            $invoice_data['type_picture'] = "";
          }
            if($requests_payments = RequestPayment::where('request_id' , $requests->id)->first()){
                //do nothing
            }else{
                $requests_payments = $request_payment;
            }
                $distance_travel = round($requests_payments->distance_travel, 3);
                $invoice_data['base_price'] = $base_price;
                $invoice_data['min_fare'] = $min_fare;
                $invoice_data['tax_price'] = $tax_price;
                $invoice_data['booking_fee'] = $booking_fee;
                $invoice_data['other_price'] = 0;
                $invoice_data['total_time_price'] = $requests_payments->time_price;
                $invoice_data['total_distance_price'] = $requests_payments->distance_price;
                $invoice_data['total_time'] = $requests_payments->total_time;
                $invoice_data['distance_travel'] = $distance_travel;
                $invoice_data['sub_total'] = $semi_total;
                $invoice_data['total'] = $requests_payments->total;
          $invoice_array_conver = array();
          array_push($invoice_array_conver,$invoice_data);
        // Send Push Notification to User
        $title = Helper::tr('request_complete_payment_title');
        // $message = $invoice_data;
        $message = Helper::tr('request_complete_payment_message');

        // Send invoice notification to the user and provider
        $subject = Helper::tr('request_completed_invoice');
        $email = Helper::get_emails(3,$requests->user_id,$requests->confirmed_provider);
        $page = "emails.provider.new_invoice";
        $toemail = "rameshrb@provenlogic.net";
        $email_send = Helper::send_email($page,$subject,$toemail,$invoice_data);
        if($email_send) {
        echo "Ok"; 
        }
        else {
            echo "Not ok";
        }
    }

    public function test_push_notifications()
    {
        $this->dispatch(new sendPushNotification(34,1,$requests->id,$title,$message,''));
    }

    public function adsManagement(Request $request)
    {
        $ads = Advertisement::orderBy('created_at' , 'asc')->get();
        $response_array = Helper::null_safe(array(
            'success' => true,
            'data' => $ads->toArray(),
        ));

        return response()->json($response_array, 200);
    }

    public function cancellation_reasons(Request $request)
    {
        $result = CancellationReasons::orderBy('created_at' , 'asc')->get();
        //echo count($result); exit;
        if(count($result) == 0)
        {
           $response_array = Helper::null_safe(array(
            'success' => true,
            'data' => array(),
            )); 
        }
        else 
        {
            $response_array = Helper::null_safe(array(
            'success' => true,
            'data' => $result->toArray(),
        ));
        }

        return response()->json($response_array, 200);
    }

    public function user_favourites(Request $request)
    {
        $result = UserFavourite::orderBy('created_at' , 'asc')->get();
        //echo count($result); exit;
        if(count($result) == 0)
        {
           $response_array = Helper::null_safe(array(
            'success' => true,
            'data' => array(),
            )); 
        }
        else 
        {
            $response_array = Helper::null_safe(array(
            'success' => true,
            'data' => $result->toArray(),
        ));
        }

        return response()->json($response_array, 200);
    }

    public function add_user_favourite(Request $request) {

        $validator = Validator::make(
                    $request->all(),
                    array(
                        'favourite_name' => 'required',
                        'address' => 'required',
                        'latitude' => 'required',
                        'longitude' => 'required',
                    )
                );

        if ($validator->fails())
        {
           $error_messages = implode(',', $validator->messages()->all());
           $response_array = array('success' => false , 'error' => Helper::get_error_message(101) , 'error_code' => 101 , 'error_messages' => $error_messages);

        } else {

            $favs = new UserFavourite;
            $favs->user_id = $request->id;
            $favs->favourite_name = $request->favourite_name;
            $favs->address = $request->address;
            $favs->latitude = $request->latitude;
            $favs->longitude = $request->longitude;
            $favs->save();

            $response_array = Helper::null_safe(array('success' => true));
        }

        $response = response()->json($response_array,200);
        return $response;
    }

    public function delete_user_favourite(Request $request) {

        $fav_id = $request->fav_id;

        $validator = Validator::make(
            $request->all(),
            array(
                'fav_id' => 'required|integer|exists:user_favourites,id,user_id,'.$request->id,
            ),
            array(
                'exists' => 'The :attribute doesn\'t belong to user:'.$request->id
            )
        );

        if ($validator->fails()) {
           $error_messages = implode(',', $validator->messages()->all());
           $response_array = array('success' => false , 'error' => Helper::get_error_message(101) , 'error_code' => 101 , 'error_messages' => $error_messages);
        } else {

            UserFavourite::where('id',$fav_id)->delete();

            $response_array = array('success' => true );
        }

        return response()->json($response_array , 200);
    }

    public function logout(Request $request)
    {
        $user = User::find($request->id);

        if($user)
        {
            // Modify the existing tokens with new tokens
            $user->token = Helper::generate_token();

            //Reset the Expiry Time to Past value
            $user->token_expiry = time() - (30 * 60);

            // Reset the device token
            $user->device_token = '';

            $user->save();

            $response_array = array(
                'success' => true
            );
        }
        else
        {
            $response_array = array(
                'success' => false,
                'error' => Helper::get_error_message(104),
                'error_code' => 104
            );
            $response_array = Helper::null_safe($response_array);
        }

        return response()->json($response_array, 200);
    }

}
