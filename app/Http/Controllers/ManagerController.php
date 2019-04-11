<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Helper;

use App\User;

use App\ManagerRequest;

use App\Provider;

use App\Document;

use App\ProviderDocument;

use App\ProviderRating;

use App\ChatMessage;

use App\Admin;

use App\Corporate;

use App\CallCenterManager;

use App\ServiceType;

use App\Requests;

use App\UserRating;

use App\RequestPayment;

use App\ProviderService;

use App\Settings;

use Validator;

use Hash;

use Mail;

use DB;

use Auth;

use Redirect;

use Setting;

use Log;

use App\Jobs\NormalPushNotification;

use App\Jobs\sendPushNotification;

use App\PromoCode;

use App\RequestsMeta;

use App\HourlyPackage;

use App\AirportDetail;

use App\AirportPrice;

use App\LocationDetail;

use App\Cards;

define('UNIT_DISTANCE', 'kms,miles');
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

if (!defined('WAITING_TO_RESPOND')) define('WAITING_TO_RESPOND', 1);
if (!defined('WAITING_TO_RESPOND_NORMAL')) define('WAITING_TO_RESPOND_NORMAL',0);

if (!defined('LIMITED_OFFER')) define('LIMITED_OFFER',0);
if (!defined('GLOBAL_OFFER')) define('GLOBAL_OFFER',1);

class ManagerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('manager');
    }

    /**
     * Show the Corporate dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard() {
        // if(Auth::check()){
        //     echo"Auth chekeed manager";exit;
        // }
        // //dd(Auth::guard('manager')->user());
        // echo "im in manager dashboard";
        //dd(Session());
        //dd(Auth::check());
        $total = RequestPayment::sum('total');

        $paypal_total = RequestPayment::where('payment_mode','paypal')->sum('total');

        $card_total = RequestPayment::where('payment_mode','card')->sum('total');

        $cod_total = RequestPayment::where('payment_mode','cod')->sum('total');


        $total_requests = Requests::count();

        $completed = Requests::where('status','5')->count();

        $ongoing = Requests::where('status','4')->count();

        $cancelled = Requests::where('status','6')->count();


        $provider_reviews = UserRating::leftJoin('providers', 'user_ratings.provider_id', '=', 'providers.id')
                            ->leftJoin('users', 'user_ratings.user_id', '=', 'users.id')
                            ->select('user_ratings.id as review_id', 'user_ratings.rating', 'user_ratings.comment', 'users.first_name as user_first_name', 'users.last_name as user_last_name', 'providers.first_name as provider_first_name', 'providers.last_name as provider_last_name', 'users.id as user_id', 'users.picture as user_picture', 'providers.id as provider_id', 'user_ratings.created_at')
                            ->orderBy('user_ratings.created_at', 'desc')
                            ->get();

        $get_registers = get_register_count();
        $recent_users = get_recent_users();
        $total_revenue = 10;

        $view = last_days(10);

        return view('manager.dashboard')
                ->with('total' , $total)
                ->with('paypal_total' , $paypal_total)
                ->with('card_total' , $card_total)
                ->with('cod_total' , $cod_total)
                ->with('view' , $view)
                ->with('get_registers' , $get_registers)
                ->with('recent_users' , $recent_users)
                ->with('provider_reviews' , $provider_reviews)
                ->with('total_requests' , $total_requests)
                ->with('completed' , $completed)
                ->with('cancelled' , $cancelled)
                ->with('ongoing' , $ongoing)
                ->withPage('dashboard')
                ->with('sub_page','');
    }

    public function profile() {

        $corporate = CallCenterManager::first();
        return view('manager.profile')->with('call_centers' , $corporate)->withPage('profile')->with('sub_page','');
    }

    public function profile_process(Request $request) {
        // dd($request);
        $validator = Validator::make( $request->all(),array(
                'name' => 'max:255',
                'email' => 'email|max:255',
                'mobile' => 'digits_between:6,13',
                'address' => 'max:300',
                'id' => 'required|exists:call_center_managers,id'
            )
        );

        if($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        } else {

            $corporate = CallCenterManager::find($request->id);

            $corporate->name = $request->has('name') ? $request->name : $corporate->name;

            $corporate->email = $request->has('email') ? $request->email : $corporate->email;

            $corporate->mobile = $request->has('mobile') ? $request->mobile : $corporate->mobile;

            $corporate->gender = $request->has('gender') ? $request->gender : $corporate->gender;

            $corporate->address = $request->has('address') ? $request->address : $corporate->address;

            if($request->hasFile('picture')) {
                Helper::delete_picture($corporate->picture);
                $corporate->picture = Helper::normal_upload_picture($request->picture);
            }

            $corporate->remember_token = Helper::generate_token();
            $corporate->is_activated = 1;
            $corporate->save();

            return back()->with('flash_success', Helper::tr('manager_not_profile'));

        }

    }

    public function change_password(Request $request) {

        $old_password = $request->old_password;
        $new_password = $request->password;
        $confirm_password = $request->confirm_password;

        $validator = Validator::make($request->all(), [
                'password' => 'required|min:6',
                'old_password' => 'required',
                'confirm_password' => 'required|min:6',
                'id' => 'required|exists:call_center_managers,id'
            ]);

        if($validator->fails()) {

            $error_messages = implode(',',$validator->messages()->all());

            return back()->with('flash_errors', $error_messages);

        } else {

            $corporate = CallCenterManager::find($request->id);

            if(Hash::check($old_password,$corporate->password))
            {
                $corporate->password = Hash::make($new_password);
                $corporate->save();

                return back()->with('flash_success', "Password Changed successfully");

            } else {
                return back()->with('flash_error', "Pasword is mismatched");
            }
        }

        $response = response()->json($response_array,$response_code);

        return $response;
    }


    public function create_request_form()
    {
        //$corporate = CallCenterManager::first();
        return view('manager.create_request')->withPage('create_request')->with('sub_page','');
    }

    public function create_request(Request $request)
    {

        
            $validator = Validator::make(
                $request->all(),
                array(
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'email' => 'required|email|max:255',
                    'mobile' => 'required|digits_between:6,13',
                    // 'address' => 'required|max:300',
                    // 'picture' => 'required|mimes:jpeg,jpg,bmp,png',

                ),
                array( 'required' => 'Email field is required',
                          'exists' => 'Invalid Mail Id',
                          'unique' => 'The Mail Id is already taken',)
            );
        

        if($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {
            

                  $user_email = $request->email;
                  $user_exists = User::where('email',trim($request->email))->first();
                  if(!$user_exists){
                    //Add New User
                    $user = new User;
                    $new_password = time();
                    $new_password .= rand();
                    $new_password = sha1($new_password);
                    $new_password = substr($new_password, 0, 8);
                    $user->password = Hash::make($new_password);
                    $user->is_registered = 0;
                    //$user->picture = Helper::upload_picture($picture);
                    $user->first_name = $request->first_name;
                    $user->last_name = $request->last_name;
                    $user->email = $request->email;
                    $user->mobile = $request->mobile;
                    $user->token = Helper::generate_token();
                    $user->token_expiry = Helper::generate_token_expiry();
                    //$user->gender = $gender;
                    $user->is_activated = 1;
                    $user->is_approved = 1;
                    $user->payment_mode = COD;
                    //$user->address = $address;
                    $user->timezone = "Asia/Kolkata";
                    $user->save();

                   // if($request->id == ''){
                    $email_data['first_name'] = $user->first_name;
                    $email_data['last_name'] = $user->last_name;
                    $email_data['password'] = $new_password;
                    $email_data['email'] = $user->email;

                    $subject = Helper::tr('user_welcome_title');
                    $page = "emails.admin.welcome";
                    $email = $user->email;
                    Helper::send_email($page,$subject,$email,$email_data);
                    //}

                    
                  }else{
                    $user_exists->mobile = $request->mobile;
                    $user_exists->save();
                  }
                  $data = array();
                  $data['first_name'] = $request->first_name;
                  $data['last_name'] = $request->last_name;
                  $data['email'] = $request->email;  
                  $data['uniq_id'] = uniqid();
                  Log::info($data);

                  //request_status_type dropdown menu
                  $request_status_types = array(1=>'NORMAL_REQUEST', 2=>'HOURLY_PACKAGE', 3=>'AIRPORT_PACKAGE');

                    //paymentmodes dropdown menu
                    $payment_modes = array();
                    $modes = Settings::whereIn('key' , array('cod','paypal','card','walletbay'))->where('value' , 1)->get();
                    if($modes) {
                        foreach ($modes as $key => $mode) {
                            $payment_modes[$key] = $mode->key;
                        }
                    }

                    //$response_array = Helper::null_safe(array('success' => true , 'payment_modes' => $payment_modes));

                  //servicetypes dropdown menu
                  $service_types=ServiceType::orderBy('created_at' , 'asc')->get();

                  return view('manager.proceed_request')->withPage('create_request')->with('sub_page','')->with('data',$data)->with('service_types',$service_types)->with('request_status_types',$request_status_types)->with('payment_modes',$payment_modes);
            }
        
    }

    public function request_later(Request $request) {
        // $current_date = Helper::add_date(date('Y-m-d H:00:00') ,2); // To check the income date is greater than the current date

        // $validator = Validator::make(
        //         $request->all(),
        //         array(
        //             's_latitude' => 'required|numeric',
        //             'd_longitude' => 'required|numeric',
        //             'service_type' => 'numeric|exists:service_types,id',
        //             'requested_time' => 'required',
        //             'hourly_package_id' => 'numeric|exists:hourly_packages,id',
        //             'airport_price_id' => 'numeric|exists:airport_prices,id',
        //             'request_status_type' => 'numeric'
        //         ), array( 'required' => 'Location Selected was incorrect! Please try again!',
        //                   'exists' => 'Invalid package, please try valid package'));

        // if ($validator->fails())
        // {
        //     $error_messages = implode(',', $validator->messages()->all());
        //     $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        // } else {


            Log::info('Create schedule request start');

            // Check the user filled the payment details
            $user = User::where('email',trim($request->email))->first();
            //$user = User::find($request->id);
            $userTimezone = $user->timezone;

            if(!$user->payment_mode) {
                // Log::info('Payment Mode is not available');
                $response_array = array('success' => false , 'error' => Helper::get_error_message(134) , 'error_code' => 134);
            } else {

                $allow = DEFAULT_TRUE;

                //making COD by default if request is made by manager
                if(Auth::guard('manager')->user()){
                    $user->payment_mode = COD; 
                    $manager_id = Auth::guard('manager')->user()->id;
                }

                // if the payment mode is CARD , check if any default card is available
                // if($user->payment_mode == CARD) {
                //     if($user_card = Cards::find($user->default_card)) {
                //         $allow = DEFAULT_TRUE;
                //     }
                // } else {
                //     $allow = DEFAULT_TRUE;
                // }

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

                    $check_requests = Requests::where('user_id' , $user->id)->whereNotIn('status' , $check_status)->where('later' , DEFAULT_FALSE)->count();

                    if($check_requests == 0) {

                        // Check any scheduled requests from current time +2hours , -1hour
                        $check_later_requests = Helper::check_later_request($request->id,$request->requested_time,DEFAULT_TRUE);

                        if(!$check_later_requests) {

                                Log::info('Previous requests check is done');
                                $service_type = $request->service_type; // Get the service type


                                $latitude = $request->s_latitude;
                                $longitude = $request->s_longitude;
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
                                $requests->manager_id = $manager_id;
                                $requests->manager_uniq_id = $request->uniq_id;
                                $requests->amount = $request->total;
                                //if($service_type)
                                $requests->request_type = $request->service_id;

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
                                $response_array = array('success' => false, 'error' => "This User's normal request is already in progress, cannot schedule again");
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

        //}

            print_r(json_encode($response_array));
        //$response = response()->json($response_array, 200);
        //return $response;

    }

    public function hourly_package_fare(Request $request)
    {
        
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
                    'hourly_package_details' => json_encode($hourly_package_fare),
                    //'price' => $hourly_package_fare->price,
                  ));
            }else{
                $response_array = array('success' => false , 'error_messages' => Helper::get_error_message(160) , 'error_code' => 160);
            }
        }
        print_r(json_encode($response_array));
        //return response()->json($response_array , 200);
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
        print_r(json_encode($response_array));
        //return response()->json($response_array , 200);
    }

    public function location_details(Request $request)
    {
        // $validator = Validator::make(
        //   $request->all(),
        //   array(
        //     'key' => 'required',
        //   ));

        // if ($validator->fails()) {
        //     $error_messages = implode(',',$validator->messages()->all());
        //     $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        // } else {
            //if($location_details = LocationDetail::where('name','like', '%'.$request->key.'%')->get()) {
            if($location_details = LocationDetail::all()) {

                $response_array = Helper::null_safe(array(
                    'success' => true,
                    'location_details' => $location_details,
                  ));
            }else{
                $response_array = array('success' => false , 'error' => Helper::get_error_message(160) , 'error_code' => 160);
            }
        //}
        print_r(json_encode($response_array));
        //return response()->json($response_array , 200);
    }


    public function airport_package_fare(Request $request)
    {
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
                $airport_lat_long = AirportDetail::where('id',$request->airport_details_id)->select('name','latitude','longitude')->first();
                $location_lat_long = LocationDetail::where('id',$request->location_details_id)->select('name','latitude','longitude')->first();
                $response_array = Helper::null_safe(array(
                    'success' => true,
                    'airport_price_details' => $airport_package_fare,
                    'airport_lat_long' => $airport_lat_long,
                    'location_lat_long' => $location_lat_long,
                  ));
            }else{
                $response_array = array('success' => false , 'error' => Helper::get_error_message(160) , 'error_code' => 160);
            }
        }
        print_r(json_encode($response_array));
        //return response()->json($response_array , 200);
    }


    public function proceed_request(Request $request)
    {
       print_r($request->all());
    }

    // finding the near-by providers
    public function find_providers(Request $request) {
        //print_r(json_encode($request->all()));exit;
        Log::info('send_request'.print_r($request->all() ,true));

            if($request->type == "re_assign"){
                $request = Requests::find($request->request_id);
                $user = User::find($request->user_id);
                $request->email = $user->email;
            }
            $user = User::where('email',trim($request->email))->first();

            //making COD by default if request is made by manager
            if(Auth::guard('manager')->user()){
                $user->payment_mode = COD; 
            }

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

                if($user->payment_mode == WALLETBAY){
                    //echo $user->payment_mode;exit;
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
                        $response_array = array('success' => false , 'error' => Helper::get_error_message(163) ,'error_code' => 163);
                    }
                    else{
                        $allow == DEFAULT_TRUE;
                    }


                }


                // $hourly_package_valid = DEFAULT_FALSE;
                // $airport_package_valid = DEFAULT_FALSE;
                // $request_status_type = DEFAULT_FALSE;

                 if($allow == DEFAULT_TRUE) {

                //     //Check request status type for hourly package,airport and normal request.
                //     if($request->has('request_status_type')){
                //         if($request->request_status_type == 1){
                //             $request_status_type = NORMAL_REQUEST;
                //         }elseif ($request->request_status_type == 2) {
                //             $request_status_type = HOURLY_PACKAGE;
                //         }elseif ($request->request_status_type == 3) {
                //             $request_status_type = AIRPORT_PACKAGE;
                //         }
                //     }else{
                //         $request_status_type = NORMAL_REQUEST;
                //     }

                //     // Check for hourly package status
                //     if($request_status_type == HOURLY_PACKAGE){
                //         if($request->has('hourly_package_id')){
                //             if($hourly_package_details = HourlyPackage::where('id',$request->hourly_package_id)->first()){
                //                 $hourly_package_valid = DEFAULT_TRUE;
                //             }else{
                //                 $response_array = array('success' => false , 'error' => Helper::get_error_message(142) ,'error_code' => 142);
                //                 //$response = response()->json($response_array, 200);
                //                 //return $response;
                //                 print_r(json_encode($response_array));exit;
                //             }
                //         }
                //     } elseif($request_status_type == AIRPORT_PACKAGE) {
                //         if($request->has('airport_price_id')) {
                //             $airport_price_details = AirportPrice::where('id',$request->airport_price_id)->first(); 
                //             $airport_package_valid = DEFAULT_TRUE;
                //         }
                //     }

                    

                            //Log::info('Previous requests check is done');
                            $service_type = $request->service_id; // Get the service type

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
                                    // // Send push notification to User

                                    // $title = Helper::get_push_message(601);
                                    // $messages = Helper::get_push_message(602);
                                    // $this->dispatch( new NormalPushNotification($user->id, 0,$title, $messages));
                                    $response_array = array('success' => false, 'error' => Helper::get_error_message(112), 'error_code' => 112);
                                    //print_r($response_array);echo "no providerrrrrrr";
                                }
                            }


                            // Sort the providers based on the waiting time
                            $sort_waiting_providers = Helper::sort_waiting_providers($search_providers);
                            // Get the final providers list
                            $final_providers = $sort_waiting_providers['providers'];
                                    //
                            $check_waiting_provider_count = $sort_waiting_providers['check_waiting_provider_count'];

                            if(count($final_providers) == $check_waiting_provider_count){
                                //return response()->json($response_array = array('success' => false, 'error' => Helper::get_error_message(112), 'error_code' => 112) , 200);
                              $response_array = array('success' => false, 'error' => Helper::get_error_message(112), 'error_code' => 112);
                              //$response = response()->json($response_array, 200);
                              print_r(json_encode($response_array));exit;

                            }

                            /*Promo Section*/
                            $getpromo_code = '';
                            $getpromo_scope = '';
                            $current = date('Y-m-d h:i:s');
                            $getPromoOffer = PromoCode::where('scope', GLOBAL_OFFER)->whereRaw('"'.$current.'" between `start` and `end`')->first();
                            Log::info("***** Promo Code Validation *****");
                            Log::info("Checking global offer: ".count($getPromoOffer));
                            if($getPromoOffer) 
                            {
                                Log::info("Global offer id is: ".$getPromoOffer->id);
                                $getpromo_code = $getPromoOffer->id; // auto increment Id from promo table
                                $getpromo_scope = GLOBAL_OFFER;
                            } 
                            else 
                            {
                                if($request->promo_code !='') 
                                {
                                    $getPromo = PromoCode::where('coupon_code', $request->promo_code)->where('scope', LIMITED_OFFER)->whereRaw('"'.$current.'" between `start` and `end`')->first();
                                    if($getPromo) {
                                        $getpromo_code = $request->promo_code;
                                        $getpromo_scope = LIMITED_OFFER;
                                    } else {
                                        Log::info("Invalid promo code given: ".$request->promo_code);
                                    }
                                }
                            }

                                // Save all the final providers
                                $first_provider_id = 0;
                                $provider_ids =[];
                                $provider_names = [];
                                if($final_providers) {
                                    foreach ($final_providers as $key => $final_provider) {

                                            $provider = Provider::Where('id',$final_provider)->first();
                                            if($provider->waiting_to_respond != 1){
                                              $provider_ids[] = $final_provider;
                                $provider_names[] = $provider->first_name.' '.$provider->last_name;
                                            }
                                            
                                    }
                                    
                                }

                                $hourly_package_id=$request->hourly_package_id?$request->hourly_package_id:"";
                                $airport_price_id=$request->airport_price_id?$request->airport_price_id:"";

                                
                                $providers = array_combine($provider_ids, $provider_names);
                                $response_array = array(
                                    'success' => true,
                                    'request_status_type' => $request->request_status_type,
                                    'hourly_package_id' => $hourly_package_id,
                                    'airport_price_id' => $airport_price_id,
                                    'manager_id' => Auth::guard('manager')->user()->id,
                                    'user_id' => $user->id,
                                    'service_id' => $request->service_id,
                                    'providers' => json_encode($providers),
                                    'address' => $request->s_address,
                                    'latitude' => $request->s_latitude,
                                    'longitude' => $request->s_longitude,
                                    'd_address' => $request->d_address,
                                    'd_latitude' => $request->d_latitude,
                                    'd_longitude' => $request->d_longitude,
                                );

                                $response_array = Helper::null_safe($response_array); Log::info('find providers  end');
                                
                } else {
                    $response_array = array('success' => false , 'error' => Helper::get_error_message(142) ,'error_code' => 142);
                    //print_r($response_array);
                }
            }
        
            print_r(json_encode($response_array));
            

    }

    // Manual request
    public function manual_create_request(Request $request) {
        $request_type = $request->type;
        $request_id = $request->request_id;
        $provider_id = $request->provider_id;
//print_r(json_encode($request->all()));exit;
      if($request_type == "re_assign"){
      //print_r(json_encode($request->all()));exit;

                $request = Requests::find($request->request_id);
      //print_r(json_encode($request));exit;

                $user = User::find($request->user_id);
                $request->email = $user->email;
        //print_r(json_encode(Requests::find($request->request_id)));exit;

            }
      
      $manager_id = Auth::guard('manager')->user()->id;
    
      
        // $validator = Validator::make(
        //         $request->all(),
        //         array(
        //             's_latitude' => 'required|numeric',
        //             's_longitude' => 'required|numeric',
        //             'service_id' => 'numeric|exists:service_types,id',
        //             'provider_id' => 'required|exists:providers,id',
        //         ));

        // if ($validator->fails())
        // {
        //     $error_messages = implode(',', $validator->messages()->all());
        //     $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        // } else {
            Log::info('Create manual request start');

            

            //Check the user filled the payment details
            //$user = CallCenterManager::find($manager_id);
            $user = User::where('email',trim($request->email))->first();
            // Save the user location
            $user->latitude = $request->s_latitude;
            $user->longitude = $request->s_longitude;
            $user->save();

            if(!$user->payment_mode) {
                Log::info('Payment Mode is not available');
                $response_array = array('success' => false , 'error' => Helper::get_error_message(134) , 'error_code' => 134);
            } else {

                // $allow = DEFAULT_FALSE;
                // // if the payment mode is CARD , check if any default card is available
                // if($user->payment_mode == CARD) {
                //     if($user_card = Cards::find($user->default_card)) {
                //         $allow = DEFAULT_TRUE;
                //     }
                // } else {
                //     $allow = DEFAULT_TRUE;
                // }

                //if($allow == DEFAULT_TRUE) {
                if($request_type != "re_assign"){
                    $hourly_package_valid = DEFAULT_FALSE;
                    $airport_package_valid = DEFAULT_FALSE;
                    $request_status_type = DEFAULT_FALSE;
//print_r(json_encode($request->all()));exit;
                    //if($allow == DEFAULT_TRUE) {

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
                                    //$response = response()->json($response_array, 200);
                                    //return $response;
                                   // print_r(json_encode($response_array));exit;
                                }
                            }
                        } elseif($request_status_type == AIRPORT_PACKAGE) {
                            if($request->has('airport_price_id')) {
                                $airport_price_details = AirportPrice::where('id',$request->airport_price_id)->first(); 
                                $airport_package_valid = DEFAULT_TRUE;
                            }
                        }
                    }

                    if($request_type == "re_assign"){
                        $request->provider_id = $provider_id;
                    }

                    // Check the provider is available
                    if($provider = Provider::where('id' , $request->provider_id)->where('is_available' , DEFAULT_TRUE)->where('is_activated' , DEFAULT_TRUE)->where('is_approved' , DEFAULT_TRUE)->where('waiting_to_respond' ,DEFAULT_FALSE)->first()) {
                        //print_r(json_encode($provider));exit;

                      //print_r(json_encode($provider));exit;
                        //Check already request exists
                        $check_status = array(REQUEST_NO_PROVIDER_AVAILABLE,REQUEST_CANCELLED,REQUEST_COMPLETED);

                        $check_requests = Requests::where('user_id' , $user->id)->whereNotIn('status' , $check_status)->count();
                        //print_r(json_encode($request->all()));exit;
                        //$check_requests = 0;
                        // if($request_type == "re_assign"){
                        //     $check_requests = 0;
                        // }
                        if($check_requests == 0) {              
                            Log::info('Previous requests check is done');
                            // Check any scheduled requests from current time +2hours , -1hour
                            $check_later_requests = Helper::check_later_request($request->id,date('Y-m-d H:i:s'),DEFAULT_TRUE);

                            if(!$check_later_requests) {
                            Log::info('scheduled requests check is done');

                                if($request_type == "re_assign"){
                                    $requests = Requests::find($request_id);
                                    $release_previous_provider = Provider::find($requests->provider_id);
                                    if($requests->provider_status == PROVIDER_ACCEPTED ){
                                        $release_previous_provider->is_available = PROVIDER_NOT_AVAILABLE;
                                    }else{
                                        $release_previous_provider->is_available = PROVIDER_AVAILABLE;
                                    }
                                    $release_previous_provider->waiting_to_respond = WAITING_TO_RESPOND_NORMAL;
                                    $release_previous_provider->save();
                                    //print_r(json_encode($provider_id))
                                    $requests->status = REQUEST_NEW;
                                    $requests->confirmed_provider = NONE;
                                    $requests->request_start_time = date("Y-m-d H:i:s");
                                    $requests->provider_id = $provider_id;
                                    $requests->save();
                                    
                                }else{
                                    // Create Requests
                                    $requests = new Requests;
                                    $requests->manager_id = $manager_id;
                                    $requests->user_id = $user->id;
                                    $requests->manager_uniq_id = $request->uniq_id;
                                    $requests->provider_id = $request->provider_id;


                                    if($request->service_id){
                                    $requests->request_type = $request->service_id;
                                    }

                                      $requests->request_status_type = $request->request_status_type;
                                    $requests->amount = $request->total;
                                    $requests->status = REQUEST_NEW;
                                    $requests->confirmed_provider = NONE;
                                    $requests->request_start_time = date("Y-m-d H:i:s");
                                    $requests->s_address = $request->s_address ? $request->s_address : "";
                                    $requests->d_address = $request->d_address ? $request->d_address : "";
                                    $requests->d_latitude = $request->d_latitude ? $request->d_latitude : "";
                                    $requests->d_longitude = $request->d_longitude ? $request->d_longitude : "";

                                    if($request_status_type == HOURLY_PACKAGE && $hourly_package_valid == DEFAULT_TRUE){
                                        $requests->request_status_type = HOURLY_PACKAGE;
                                        $requests->hourly_package_id = $request->hourly_package_id;
                                    }elseif($request_status_type == AIRPORT_PACKAGE && $airport_package_valid == DEFAULT_TRUE){
                                        $requests->request_status_type = AIRPORT_PACKAGE;
                                        $requests->airport_price_id = $request->airport_price_id;
                                    }else{
                                        $requests->request_status_type = NORMAL_REQUEST;
                                    }


                                    if($request->s_latitude){ $requests->s_latitude = $request->s_latitude; }
                                    if($request->s_longitude) { $requests->s_longitude = $request->s_longitude; }

                                    $requests->save();
                                }
                                
                                if($requests) {
                                    $requests->status = REQUEST_WAITING;
                                    $requests->save();
                                    $request_meta = new RequestsMeta;

                                    $request_meta->status = REQUEST_META_OFFERED;  // Request status change

                                    // Availablity status change
                                        $provider->waiting_to_respond = WAITING_TO_RESPOND;
                                        $provider->save();


                                            $title = Helper::get_push_message(604);
                                            $message = "You got a new request ";

                                            $this->dispatch(new sendPushNotification($request->provider_id,2,$requests->id,$title,$message,''));

                                    // Send push notifications to the first provider
                                    // $title = Helper::get_push_message(604);
                                    // $message = "You got a new request from".$user->name;

                                    // $this->dispatch(new sendPushNotification($request->provider_id,2,$requests->id,$title,$message,''));

                                    // Push End

                                    $request_meta->request_id = $requests->id;
                                    if($request_type == "re_assign"){
                                        $request_meta->provider_id = $provider_id;
                                    }else{
                                        $request_meta->provider_id = $request->provider_id;
                                    }
                                    
                                    
                                    $request_meta->save();

                                    $response_array = array(
                                        'success' => true,
                                        'manager_id' => $requests->manager_id,
                                        'user_id' => $requests->user_id,
                                        'manager_uniq_id' => $requests->manager_uniq_id,
                                        'request_id' => $requests->id,
                                        'request_status_type' => $requests->request_status_type,
                                        'current_provider' => $requests->provider_id,
                                        'service_id' => $requests->request_type,
                                        'total' => $requests->amount,
                                        's_address' => $requests->s_address,
                                        's_latitude' => $requests->s_latitude,
                                        's_longitude' => $requests->s_longitude,
                                        'd_address' => $requests->d_address,
                                        'd_latitude' => $requests->d_latitude,
                                        'd_longitude' => $requests->d_longitude,
                                    );

                                    $response_array = Helper::null_safe($response_array); Log::info('Create request end');
                                } else {
                                    $response_array = array('success' => false , 'error' => Helper::get_error_message(126) , 'error_code' => 126 );
                                }
                            }else{
                               $response_array = array('success' => false , 'error' => Helper::get_error_message(150) , 'error_code' => 150); 
                            }

                        } else {
                            $response_array = array('success' => false , 'error' => Helper::get_error_message(164) , 'error_code' => 164);
                        }

                    } else {
                        $response_array = array('success' => false , 'error' => Helper::get_error_message(153) ,'error_code' => 153);
                    }
                // } else {
                //     $response_array = array('success' => false , 'error' => Helper::get_error_message(142) ,'error_code' => 142);
                // }
            }
          //}
        
        print_r(json_encode($response_array));
        //$response = response()->json($response_array, 200);
        //return $response;
    }


    public function cancel_request(Request $request) {

        $user_id = Auth::guard('manager')->user()->id;
        //$user_id = $request->id;
//echo $request->request_id;
        // $validator = Validator::make(
        //     $request->all(),
        //     array(
        //         'request_id' => 'required|numeric|exists:requests,id,manager_id,'.$user_id,
        //         //'request_id' => 'required|numeric|exists:requests,id,user_id,'.$user_id,
        //     ));

        // if ($validator->fails())
        // {
        //     $error_messages = implode(',', $validator->messages()->all());
        //     $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages'=>$error_messages);
        // }else
        // {
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
                // Check whether request eligible for cancellation

                // if(in_array($providerStatus, $allowedCancellationStatuses)) {

                    // Update status of the request to cancellation
                    $requests->status = REQUEST_CANCELLED;
                    $requests->save();

                    // If request has confirmed provider then release him to available status
                   // if($requests->confirmed_provider != DEFAULT_FALSE){
                    if($requests->provider_id != DEFAULT_FALSE){

                        //$provider = Provider::find( $requests->confirmed_provider );
                        $provider = Provider::find( $requests->provider_id );
                        $provider->is_available = PROVIDER_AVAILABLE;
                        $provider->waiting_to_respond = WAITING_TO_RESPOND_NORMAL;
                        $provider->save();

                        // Send Push Notification to Provider
                        $title = Helper::tr('cancel_by_user_title');
                        $message = Helper::tr('cancel_by_user_message');

                        // $this->dispatch(new sendPushNotification($requests->confirmed_provider,2,$requests->id,$title,$message,''));

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

                        $page = "emails.user.request_cancel";
                        // $email_send = Helper::send_email($page,$subject,$provider->email,$email_data);
                    }

                    // No longer need request specific rows from RequestMeta
                    RequestsMeta::where('request_id', '=', $request_id)->delete();

                    // $response_array = Helper::null_safe(array('success' => true));

                return back()->with('flash_success', 'Request cancelled sucessfully!!');


                    //$response_array = Helper::null_safe(array('success' => true,'request_id' => $request->id));

                // } else {
                //     $response_array = array( 'success' => false, 'error' => Helper::get_error_message(114), 'error_code' => 114 );
                // }

            } else {
                return back()->with('flash_error', 'Sorry!! cancelled already');

                //$response_array = array( 'success' => false, 'error' => Helper::get_error_message(113), 'error_code' => 113 );
            }
        //}
       
        //$response = response()->json($response_array, 200);
        //return $response;
    }


  

        // Automated Request
    public function send_request(Request $request) {
      
        Log::info('send_request'.print_r($request->all() ,true));

        $validator = Validator::make(
                $request->all(),
                array(
                    's_latitude' => 'required|numeric',
                    's_longitude' => 'required|numeric',
                    'service_id' => 'numeric|exists:service_types,id',
                    
                    //'hourly_package_id' => 'numeric|exists:hourly_packages,id',
                    //'airport_price_id' => 'numeric|exists:airport_prices,id',
                    //'request_status_type' => 'numeric',
                ), array( 'required' => 'Location Selected was incorrect! Please try again!',
                          'exists' => 'Invalid package, please try valid package',
                          'unique' => 'The Mail Id is already taken'));

        if ($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        } else {
            Log::info('Create request start');


            // Check the user filled the payment details
            //$user = ManagerRequest::find($request->id);
            $manager_requests = new ManagerRequest;
            $manager_requests->caller_id = $request->uniq_id;
            $manager_requests->service_type_id = $request->service_id; 
            $manager_requests->estimated_fare = $request->total; 
            $manager_requests->first_name = $request->first_name;
            $manager_requests->last_name = $request->last_name;
            $manager_requests->email = $request->email;
            $manager_requests->payment_mode = COD;//temporarly added
            $manager_requests->default_card = "unavailable";//temporarly added
            $manager_requests->s_latitude = $request->s_latitude;
            $manager_requests->s_longitude = $request->s_longitude;
            $manager_requests->d_latitude = $request->d_latitude;
            $manager_requests->d_longitude = $request->d_longitude;
            $manager_requests->s_address = $request->s_address;
            $manager_requests->d_address = $request->d_address;
            $manager_requests->save();
            

            $user = ManagerRequest::find($manager_requests->id);
            //$user = User::find($request->id);
            // Save the user location
            // $user->latitude = $request->s_latitude;
            // $user->longitude = $request->s_longitude;
            // $user->save();

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

                if($user->payment_mode == WALLETBAY){
                    echo $user->payment_mode;exit;
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
                        $response_array = array('success' => false , 'error' => Helper::get_error_message(163) ,'error_code' => 163);
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
                                //$response = response()->json($response_array, 200);
                                //return $response;
                                print_r($response_array);exit;
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

                    $check_requests = Requests::where('manager_request_id' , $user->id)->whereNotIn('status' , $check_status)->where('later' , 0)->count();

                    //$check_requests = Requests::where('user_id' , $request->id)->whereNotIn('status' , $check_status)->where('later' , 0)->count();

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
                                    // // Send push notification to User

                                    // $title = Helper::get_push_message(601);
                                    // $messages = Helper::get_push_message(602);
                                    // $this->dispatch( new NormalPushNotification($user->id, 0,$title, $messages));
                                    $response_array = array('success' => false, 'error' => Helper::get_error_message(112), 'error_code' => 112);
                                    //print_r($response_array);echo "no providerrrrrrr";
                                }
                            }


                            // Sort the providers based on the waiting time
                            $sort_waiting_providers = Helper::sort_waiting_providers($search_providers);

                            // Get the final providers list
                            $final_providers = $sort_waiting_providers['providers'];
                                    //
                            $check_waiting_provider_count = $sort_waiting_providers['check_waiting_provider_count'];

                            if(count($final_providers) == $check_waiting_provider_count){
                                //return response()->json($response_array = array('success' => false, 'error' => Helper::get_error_message(112), 'error_code' => 112) , 200);
                              $response_array = array('success' => false, 'error' => Helper::get_error_message(112), 'error_code' => 112);
                              //$response = response()->json($response_array, 200);
                              print_r($response_array);exit;

                            }

                            /*Promo Section*/
                            $getpromo_code = '';
                            $getpromo_scope = '';
                            $current = date('Y-m-d h:i:s');
                            $getPromoOffer = PromoCode::where('scope', GLOBAL_OFFER)->whereRaw('"'.$current.'" between `start` and `end`')->first();
                            Log::info("***** Promo Code Validation *****");
                            Log::info("Checking global offer: ".count($getPromoOffer));
                            if($getPromoOffer) 
                            {
                                Log::info("Global offer id is: ".$getPromoOffer->id);
                                $getpromo_code = $getPromoOffer->id; // auto increment Id from promo table
                                $getpromo_scope = GLOBAL_OFFER;
                            } 
                            else 
                            {
                                if($request->promo_code !='') 
                                {
                                    $getPromo = PromoCode::where('coupon_code', $request->promo_code)->where('scope', LIMITED_OFFER)->whereRaw('"'.$current.'" between `start` and `end`')->first();
                                    if($getPromo) {
                                        $getpromo_code = $request->promo_code;
                                        $getpromo_scope = LIMITED_OFFER;
                                    } else {
                                        Log::info("Invalid promo code given: ".$request->promo_code);
                                    }
                                }
                            }

                            // Create Requests
                            $requests = new Requests;
                            //$requests->user_id = $user->id;
                            $requests->manager_request_id = $user->id;

                            if($service_type)
                                $requests->request_type = $service_type;

                            $requests->status = REQUEST_NEW;
                            $requests->confirmed_provider = NONE;
                            $requests->promo_code = $getpromo_code;
                            $requests->promo_scope = $getpromo_scope;
                            $requests->request_start_time = date("Y-m-d H:i:s", $request_start_time);
                            $requests->s_address = $request->s_address ? $request->s_address : "";
                            $requests->d_address = $request->d_address ? $request->d_address : "";
                            $requests->d_latitude = $request->d_latitude ? $request->d_latitude : "";
                            $requests->d_longitude = $request->d_longitude ? $request->d_longitude : "";

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
                            // dd($requests);

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
                                            // $title = Helper::get_push_message(604);
                                            // $message = "You got a new request from".$user->first_name." ".$user->last_name;

                                            // $this->dispatch(new sendPushNotification($first_provider_id,2,$requests->id,$title,$message,''));

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
                                $provider = Provider::Where('id',$first_provider_id)->first();
                                $provider_name = $provider->first_name.' '.$provider->last_name;

                                $response_array = array(
                                    'success' => true,
                                    'request_id' => $requests->id,
                                    'current_provider' => $first_provider_id,
                                    'provider_name' => $provider_name,
                                    'address' => $requests->s_address,
                                    'latitude' => $requests->s_latitude,
                                    'longitude' => $requests->s_longitude,
                                    'd_address' => $response_d_address,
                                    'd_latitude' => $response_d_latitude,
                                    'd_longitude' => $response_d_longitude,
                                );

                                $response_array = Helper::null_safe($response_array); Log::info('Create request end');
                                //print_r($response_array);
                            } else {
                                $response_array = array('success' => false , 'error' => Helper::get_error_message(126) , 'error_code' => 126 );
                                //print_r($response_array);
                            }
                        } else {
                            $response_array = array('success' => false , 'error' => Helper::get_error_message(150) , 'error_code' => 150);
                            //print_r($response_array);
                        }
                    } else {
                        $response_array = array('success' => false , 'error' => Helper::get_error_message(127) , 'error_code' => 127);
                        //print_r($response_array);
                    }

                } else {
                    $response_array = array('success' => false , 'error' => Helper::get_error_message(142) ,'error_code' => 142);
                    //print_r($response_array);
                }
            }
        }
        //$response = response()->json($response_array, 200);
        //return $response;

        //walletLow:
            //$response_array = array('success' => false , 'error' => Helper::get_error_message(163) ,'error_code' => 163);
            //$response = response()->json($response_array, 200);
            print_r($response_array);
            

    }

        // Automated Request
    public function send_request_perfect(Request $request) {
        Log::info('send_request'.print_r($request->all() ,true));

        $validator = Validator::make(
                $request->all(),
                array(
                    's_latitude' => 'required|numeric',
                    's_longitude' => 'required|numeric',
                    'service_id' => 'numeric|exists:service_types,id',
                    
                    //'hourly_package_id' => 'numeric|exists:hourly_packages,id',
                    //'airport_price_id' => 'numeric|exists:airport_prices,id',
                    //'request_status_type' => 'numeric',
                ), array( 'required' => 'Location Selected was incorrect! Please try again!',
                          'exists' => 'Invalid package, please try valid package',
                          'unique' => 'The Mail Id is already taken'));

        if ($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            $response_array = array('success' => false, 'error' => Helper::get_error_message(101), 'error_code' => 101, 'error_messages' => $error_messages);
        } else {
            Log::info('Create request start');

            
            
            //Array ( [uniq_id] => 59ce371b45bdd [service_type] => 1 [total] => 0 [first_name] => sd,fnsbk [last_name] => mkfbsmkb [email] => nasheeda@provenlogic.net [s_latitude] => 12.9081357 [s_longitude] => 77.64760799999999 [d_latitude] => 12.9165757 [d_longitude] => 77.61011630000007 [origin-input] => HSR Layout, Bengaluru, Karnataka, India [destination-input] => BTM Layout, Bengaluru, Karnataka, India [type] => on )

            // Check the user filled the payment details
            //$user = ManagerRequest::find($request->id);
            $manager_requests = new ManagerRequest;
            $manager_requests->caller_id = $request->uniq_id;
            $manager_requests->service_type_id = $request->service_id; 
            $manager_requests->estimated_fare = $request->total; 
            $manager_requests->first_name = $request->first_name;
            $manager_requests->last_name = $request->last_name;
            $manager_requests->email = $request->email;
            $manager_requests->payment_mode = COD;//temporarly added
            $manager_requests->default_card = "unavailable";//temporarly added
            $manager_requests->s_latitude = $request->s_latitude;
            $manager_requests->s_longitude = $request->s_longitude;
            $manager_requests->d_latitude = $request->d_latitude;
            $manager_requests->d_longitude = $request->d_longitude;
            $manager_requests->s_address = $request->s_address;
            $manager_requests->d_address = $request->d_address;
            $manager_requests->save();
            

            $user = ManagerRequest::find($manager_requests->id);
            //$user = User::find($request->id);
            // Save the user location
            // $user->latitude = $request->s_latitude;
            // $user->longitude = $request->s_longitude;
            // $user->save();

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

                if($user->payment_mode == WALLETBAY){
                    echo $user->payment_mode;exit;
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
                        $response_array = array('success' => false , 'error' => Helper::get_error_message(163) ,'error_code' => 163);
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
                                //$response = response()->json($response_array, 200);
                                //return $response;
                                print_r($response_array);exit;
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

                    $check_requests = Requests::where('manager_request_id' , $user->id)->whereNotIn('status' , $check_status)->where('later' , 0)->count();

                    //$check_requests = Requests::where('user_id' , $request->id)->whereNotIn('status' , $check_status)->where('later' , 0)->count();

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
                                    // // Send push notification to User

                                    // $title = Helper::get_push_message(601);
                                    // $messages = Helper::get_push_message(602);
                                    // $this->dispatch( new NormalPushNotification($user->id, 0,$title, $messages));
                                    $response_array = array('success' => false, 'error' => Helper::get_error_message(112), 'error_code' => 112);
                                    //print_r($response_array);echo "no providerrrrrrr";
                                }
                            }


                            // Sort the providers based on the waiting time
                            $sort_waiting_providers = Helper::sort_waiting_providers($search_providers);

                            // Get the final providers list
                            $final_providers = $sort_waiting_providers['providers'];
                                    //
                            $check_waiting_provider_count = $sort_waiting_providers['check_waiting_provider_count'];

                            if(count($final_providers) == $check_waiting_provider_count){
                                //return response()->json($response_array = array('success' => false, 'error' => Helper::get_error_message(112), 'error_code' => 112) , 200);
                              $response_array = array('success' => false, 'error' => Helper::get_error_message(112), 'error_code' => 112);
                              //$response = response()->json($response_array, 200);
                              print_r($response_array);exit;

                            }

                            /*Promo Section*/
                            $getpromo_code = '';
                            $getpromo_scope = '';
                            $current = date('Y-m-d h:i:s');
                            $getPromoOffer = PromoCode::where('scope', GLOBAL_OFFER)->whereRaw('"'.$current.'" between `start` and `end`')->first();
                            Log::info("***** Promo Code Validation *****");
                            Log::info("Checking global offer: ".count($getPromoOffer));
                            if($getPromoOffer) 
                            {
                                Log::info("Global offer id is: ".$getPromoOffer->id);
                                $getpromo_code = $getPromoOffer->id; // auto increment Id from promo table
                                $getpromo_scope = GLOBAL_OFFER;
                            } 
                            else 
                            {
                                if($request->promo_code !='') 
                                {
                                    $getPromo = PromoCode::where('coupon_code', $request->promo_code)->where('scope', LIMITED_OFFER)->whereRaw('"'.$current.'" between `start` and `end`')->first();
                                    if($getPromo) {
                                        $getpromo_code = $request->promo_code;
                                        $getpromo_scope = LIMITED_OFFER;
                                    } else {
                                        Log::info("Invalid promo code given: ".$request->promo_code);
                                    }
                                }
                            }

                            // Create Requests
                            $requests = new Requests;
                            //$requests->user_id = $user->id;
                            $requests->manager_request_id = $user->id;

                            if($service_type)
                                $requests->request_type = $service_type;

                            $requests->status = REQUEST_NEW;
                            $requests->confirmed_provider = NONE;
                            $requests->promo_code = $getpromo_code;
                            $requests->promo_scope = $getpromo_scope;
                            $requests->request_start_time = date("Y-m-d H:i:s", $request_start_time);
                            $requests->s_address = $request->s_address ? $request->s_address : "";
                            $requests->d_address = $request->d_address ? $request->d_address : "";
                            $requests->d_latitude = $request->d_latitude ? $request->d_latitude : "";
                            $requests->d_longitude = $request->d_longitude ? $request->d_longitude : "";

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
                            // dd($requests);

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
                                            // $title = Helper::get_push_message(604);
                                            // $message = "You got a new request from".$user->first_name." ".$user->last_name;

                                            // $this->dispatch(new sendPushNotification($first_provider_id,2,$requests->id,$title,$message,''));

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
                                //print_r($response_array);
                            } else {
                                $response_array = array('success' => false , 'error' => Helper::get_error_message(126) , 'error_code' => 126 );
                                //print_r($response_array);
                            }
                        } else {
                            $response_array = array('success' => false , 'error' => Helper::get_error_message(150) , 'error_code' => 150);
                            //print_r($response_array);
                        }
                    } else {
                        $response_array = array('success' => false , 'error' => Helper::get_error_message(127) , 'error_code' => 127);
                        //print_r($response_array);
                    }

                } else {
                    $response_array = array('success' => false , 'error' => Helper::get_error_message(142) ,'error_code' => 142);
                    //print_r($response_array);
                }
            }
        }
        //$response = response()->json($response_array, 200);
        //return $response;

        //walletLow:
            //$response_array = array('success' => false , 'error' => Helper::get_error_message(163) ,'error_code' => 163);
            //$response = response()->json($response_array, 200);
            print_r($response_array);
            

    }


    

    public function map()
    {
        return view('manager.manager_map')->withPage('create_request')->with('sub_page','');
    }

    
    function fare_calculator(Request $request){
      // $validator = Validator::make(
      //     $request->all(),
      //     array(
      //       //'distance' => 'required',
      //       //'time' => 'required',
      //       'service_id'=>'required|exists:service_types,id',
      //       's_latitude' => 'required',
      //       's_longitude' => 'required',
      //       'd_latitude' => 'required',
      //       'd_longitude' => 'required',

      //     ));
      // $request->service_id = $service_id;
      // if ($validator->fails()) {
      //     $error_messages = implode(',', $validator->messages()->all());
      //       return back()->with('flash_errors', $error_messages);
      // } else {
  
          //$time = 0;
          //$distance = 0;
          $time = $request->time; //as secs
          $distance = $request->distance; //as m

          //if($request->has('service_id')){
              // Get base price from provider service table.
              $get_price_details = ServiceType::where('id',trim($request->service_id))->first();
              //print_r($get_price_details);exit;
              $timeMinutes = $time * 0.0166667; // from seconds to minutes
              $price_per_unit_time = $get_price_details->price_per_min*$timeMinutes;

              $min_fare   = $get_price_details->min_fare;
              $base_price = $get_price_details->base_fare;
              $tax_fee    = $get_price_details->tax_fee;
              $booking_fee    = $get_price_details->booking_fee;

              $unit = $get_price_details->distance_unit;
              if($unit == 'kms')
              {
                  $distanceKm = $distance * 0.001;
                  $setdistance_price = $get_price_details->price_per_unit_distance;
                  $price_per_unit_distance = $setdistance_price*$distanceKm;
                  
              }
              else
              {
                  $distanceMiles = $distance * 0.621371; // from kms to miles
                  $setdistance_price = $get_price_details->price_per_unit_distance;
                  $price_per_unit_distance = $setdistance_price*$distanceMiles;
              }
              $semi_total   = $base_price + $price_per_unit_distance + $price_per_unit_time + $booking_fee;
          //}
          //else {
          //       $response_array = array('success' => false,'error' => Helper::get_error_message(162),'error_code' => 162);
          //       return response()->json($response_array , 200);
          // }
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

          $response_array = [];

          $response_array =array(
            'success' => true,
            'estimated_fare' => round($total,2),
            'min_fare' => $min_fare,
            'base_price' => $base_price,
            'tax_price' => $calc_tax_fee,
            'booking_fee' => $booking_fee,
            'distance_unit' => $unit,
            'currency' => $setting->value,
            'difference' => $difference
          );
          

      //}
          $total = round($total,2);
          print_r($total);
      //return response()->json($response_array , 200);
    }

    
    public function requests()
    {
        $requests = DB::table('requests')
                ->leftJoin('providers', 'requests.confirmed_provider', '=', 'providers.id')
                //->where('providers.manager_id','=',Auth::guard('manager')->user()->id)
                ->leftJoin('users', 'requests.user_id', '=', 'users.id')
                ->leftJoin('request_payments', 'requests.id', '=', 'request_payments.request_id')
                ->select('users.first_name as user_first_name', 'users.last_name as user_last_name', 'providers.first_name as provider_first_name', 'providers.last_name as provider_last_name', 'users.id as user_id', 'providers.id as provider_id', 'requests.is_paid',  'requests.id as id', 'requests.created_at as date', 'requests.confirmed_provider', 'requests.status', 'requests.provider_status','requests.manager_id', 'requests.amount as amount',
                    //'request_payments.total as amount', 
                    'request_payments.payment_mode as payment_mode', 'request_payments.status as payment_status')
                ->orderBy('requests.created_at', 'desc')
                ->get();
        return view('manager.request')->with('requests', $requests)->withPage('requests')->with('sub_page','');
    }

    public function view_request(Request $request)
    {

        $requests = DB::table('requests')
                ->where('requests.id',$request->id)
                ->leftJoin('providers', 'requests.confirmed_provider', '=', 'providers.id')
                ->leftJoin('users', 'requests.user_id', '=', 'users.id')
                ->leftJoin('request_payments', 'requests.id', '=', 'request_payments.request_id')
                ->select('users.first_name as user_first_name', 'users.last_name as user_last_name', 'providers.first_name as provider_first_name', 'providers.last_name as provider_last_name', 'users.id as user_id', 'providers.id as provider_id', 'requests.is_paid',  'requests.id as id', 'requests.created_at as date', 'requests.confirmed_provider', 'requests.status', 'requests.provider_status', 'requests.amount', 'request_payments.payment_mode as payment_mode', 'request_payments.status as payment_status', 'request_payments.total_time as total_time','request_payments.base_price as base_price', 'request_payments.time_price as time_price', 'request_payments.tax_price as tax', 'request_payments.total as total_amount', 'requests.s_latitude as latitude', 'requests.s_longitude as longitude','requests.start_time','requests.end_time','requests.s_address as request_address','requests.before_image' , 'requests.after_image'
                  )
                ->first();
        return view('manager.request-view')->with('page' ,'requests')->with('sub-page' , "")->with('request', $requests);
    }

}
