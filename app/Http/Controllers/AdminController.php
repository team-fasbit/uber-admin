<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Helper;

use App\User;

use App\Advertisement;

use App\Provider;

use App\Document;

use App\Currency;

use App\CancellationReasons;

use App\ProviderDocument;

use App\ProviderRating;

use App\PromoCode;

use App\HourlyPackage;

use App\ChatMessage;

use App\Admin;

use App\ServiceType;

use App\AirportDetail;

use App\LocationDetail;

use App\AirportPrice;

use App\Requests;

use App\UserRating;

use App\RequestPayment;

use App\ProviderService;

use App\Settings;

use Validator;

use Hash;

use Mail;

use DB;

use Log;

use Auth;

use Redirect;

use Setting;

use Twilio\Rest\Client;

use App\Corporate;

use App\CallCenterManager;

use App\RequestsMeta;


define('UNIT_DISTANCE', 'kms,miles');
if (!defined('USER')) define('USER',1);
if (!defined('PROVIDER')) define('PROVIDER',1);
if (!defined('ADMIN')) define('ADMIN', 1);
if (!defined('SUB_ADMIN')) define('SUB_ADMIN', 0);

if (!defined('ADD')) define('ADD', 1);
if (!defined('EDIT')) define('EDIT', 2);
if (!defined('VIEW_ALL')) define('VIEW_ALL', 3);
if (!defined('DELET')) define('DELET', 4);
if (!defined('VIEW_HISTORY')) define('VIEW_HISTORY', 5);
if (!defined('VIEW_DOCUMENTS')) define('VIEW_DOCUMENTS', 6);
if (!defined('VIEW_REQUEST')) define('VIEW_REQUEST', 7);
if (!defined('APPROVE')) define('APPROVE', 8);
if (!defined('DECLINE')) define('DECLINE', 9);
if (!defined('VIEW_DETAILS')) define('VIEW_DETAILS', 10);
if (!defined('RESET_PASSWORD')) define('RESET_PASSWORD', 11);
if (!defined('RE_ASSIGN')) define('RE_ASSIGN', 12);
if (!defined('CANCEL_REQUEST')) define('CANCEL_REQUEST', 13);

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


use App\AdminTronWallet;
use App\Helpers\Tron;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin', array('except' => ['send_app_link']));
    }



    public function getTronBalance(Request $request)
    {
        return response()->json([
            'success' => true, 
            'balance' => Tron::getBalanceCurl($request->addressbase58) / pow(10, 6)
        ]);
    }



    /**
     * make default wallet
     */
    public function makeDefaultWallet(Request $request)
    {

        //disabled all default tron wallets
        AdminTronWallet::where('is_default', true)->update(['is_default' => false]);


        //make selected wallet default
        $wallet = AdminTronWallet::find($request->wallet_id);
        $wallet->is_default = true;
        $wallet->save();

        //save default wallet into setting
        $hex = Settings::where('key', 'tron_address_hex')->first() ? : new Settings;
        $hex->key = 'tron_address_hex';
        $hex->value = $wallet->address_hex;
        $hex->save();

        $base58 = Settings::where('key', 'tron_address_base58')->first() ? : new Settings;
        $base58->key = 'tron_address_base58';
        $base58->value = $wallet->address_base58;
        $base58->save();

        $pkey = Settings::where('key', 'tron_private_key')->first() ? : new Settings;
        $pkey->key = 'tron_private_key';
        $pkey->value = $wallet->private_key;
        $pkey->save();

        
        return response()->json(['success' => true], 200);

    }


    public function saveTronAddress(Request $request)
    {

        $isDefault = AdminTronWallet::count() ? false : true;

        $wallet = new AdminTronWallet;
        $wallet->address_base58 = $request->address_base58;
        $wallet->address_hex = $request->address_hex;
        $wallet->private_key = $request->private_key;
        $wallet->public_key = $request->public_key;
        $wallet->is_default = $isDefault;
        $wallet->save();

        return response()->json([
            'success' => true,
            'wallet' => $wallet
        ], 200);

    }



    
    public function createTronAddress()
    {
        $address = Tron::createdAddress();
        return response()->json($address);
    }

    /**
     * save tron api url
     */
    public function saveTronApiurl(Request $request)
    {
        $setting = Settings::where('key', 'tron_api_url')->first() ? : new Settings;
        $setting->key = 'tron_api_url';
        $setting->value = rtrim(trim($request->tron_api_url), '/');
        $setting->save();

        return response()->json(['success' => true], 200);

    }



    /**
     * show tron settings
     */
    public function showTronSettings(Request $request)
    {

        $wallets = AdminTronWallet::orderBy('created_at', 'desc')->get();
        $settings = Settings::all();
        return view('admin.tron_settings', compact('wallets', 'settings'))->withPage('settings');
    }




    /** get users tron balances */
    public function getUsersTronBalances(Request $request)
    {
        $uids = explode(',', $request->user_ids);
        $uidBalances = [];
        
        foreach($uids as $index => $id) {
            $id = (int)trim($id);
            
            try {
                $userAccount = Tron::getUserWallet($id);
                $balance = Tron::getUserBalance($id);
            } catch(\Exception $e) {
                $balance = 0;    
            }
            
            $uidBalances[] = ['id' => $id, 'balance' => $balance / pow(10, 6), 'address' => $userAccount];
        }

        return response()->json($uidBalances, 200);
    }




    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard() {

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

        return view('admin.dashboard')
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

        $admin = Admin::first();
        return view('admin.profile')->with('admin' , $admin)->withPage('profile')->with('sub_page','');
    }

    public function profile_process(Request $request) {

        $validator = Validator::make( $request->all(),array(
                'name' => 'max:255',
                'email' => 'email|max:255',
                'mobile' => 'digits_between:6,13',
                'address' => 'max:300',
                'id' => 'required|exists:admins,id'
            )
        );

        if($validator->fails()) {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        } else {

            $admin = Admin::find($request->id);

            $admin->name = $request->has('name') ? $request->name : $admin->name;

            $admin->email = $request->has('email') ? $request->email : $admin->email;

            $admin->mobile = $request->has('mobile') ? $request->mobile : $admin->mobile;

            $admin->gender = $request->has('gender') ? $request->gender : $admin->gender;

            $admin->address = $request->has('address') ? $request->address : $admin->address;

            if($request->hasFile('picture')) {
                Helper::delete_picture($admin->picture);
                $admin->picture = Helper::normal_upload_picture($request->picture);
            }

            $admin->remember_token = Helper::generate_token();
            $admin->is_activated = 1;
            $admin->save();

            return back()->with('flash_success', Helper::tr('admin_not_profile'));

        }

    }

    public function send_app_link(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'mobile' => 'required|numeric',
            ]);
        if($validator->fails()) {
            $error_messages = implode(',',$validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        } 
        else 
        {
            $getphone = str_replace('+', '', $request->mobile);
            if (substr($getphone, 0, 1) === '1'){
                $getphone = ltrim($getphone, '1');
            }
            $phone      = "+1".$getphone; 
            // echo $phone; exit;
            $six_digit_random_number = mt_rand(100000, 999999);
            $message    = "Thanks for contacting us. Please install the User App by using this link. ".env('GOOGLE_STORE_USER'); 
            Helper::send_twilio_sms($phone, $message);
            return back()->with('flash_success', 'App links has sent to your phone number.');
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
                'id' => 'required|exists:admins,id'
            ]);

        if($validator->fails()) {

            $error_messages = implode(',',$validator->messages()->all());

            return back()->with('flash_errors', $error_messages);

        } else {

            $admin = Admin::find($request->id);

            if(Hash::check($old_password,$admin->password))
            {
                $admin->password = Hash::make($new_password);
                $admin->save();

                return back()->with('flash_success', "Password Changed successfully");

            } else {
                return back()->with('flash_error', "Pasword is mismatched");
            }
        }

        $response = response()->json($response_array,$response_code);

        return $response;
    }

    public function payment()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->transactions !='' && $user->transactions !=0 && in_array(VIEW_ALL, explode(',', $user->transactions))){
            $payment = DB::table('request_payments')
                        ->leftJoin('requests','requests.id','=','request_payments.request_id')
                        ->leftJoin('users','users.id','=','requests.user_id')
                        ->leftJoin('providers','providers.id','=','requests.confirmed_provider')
                        ->select('request_payments.*','users.first_name as user_first_name','users.last_name as user_last_name','providers.first_name as provider_first_name','providers.last_name as provider_last_name')
                        ->orderBy('created_at','desc')
                        ->get();

            return view('admin.payments')->with('payments',$payment)
                        ->withPage('payments')->with('sub_page','');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function paymentSettings()
    {
        $settings = Settings::all();
        return view('admin.paymentSettings')->with('setting',$settings);
    }

    // Corporate Functions

    public function corporates()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->corporates !='' && $user->corporates !=0 && in_array(VIEW_ALL, explode(',', $user->corporates))){
            $corporates = Corporate::orderBy('created_at' , 'desc')->get();

            return view('admin.corporates')->with('corporates',$corporates)
                                          ->withPage('corporates')->with('sub_page','view-corporate');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_corporate()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->corporates !='' && $user->corporates !=0 && in_array(ADD, explode(',', $user->corporates))){
            $service_type = ServiceType::all();
            return view('admin.add-corporate')->with('service_types',$service_type)->withPage('corporates')->with('sub_page','add-corporate');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_corporate_process(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $mobile = $request->mobile;
        $gender = $request->gender;
        $picture = $request->file('picture');
        $address = $request->address;

        if($request->id != '')
        {
            $validator = Validator::make(
                $request->all(),
                array(
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255',
                    'mobile' => 'required|digits_between:6,13',
                    'address' => 'required|max:300',

                )
            );
        }
        else
        {
            $validator = Validator::make(
                $request->all(),
                array(
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:corporates,email',
                    'mobile' => 'required|digits_between:6,13',
                    'address' => 'required|max:300',
                    'picture' => 'required|mimes:jpeg,jpg,bmp,png',

                )
            );
        }

        if($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {
              if($request->id != '')
              {
                  // Edit corporate
                  $corporate = Corporate::find($request->id);
                  if($picture != ''){
                  $corporate->picture = Helper::upload_picture($picture);
                  }
              }
              else
              {
                  //Add New corporate
                  $corporate = new Corporate;
                  $new_password = time();
                  $new_password .= rand();
                  $new_password = sha1($new_password);
                  $new_password = substr($new_password, 0, 8);
                  $corporate->password = Hash::make($new_password);
                  $corporate->picture = Helper::upload_picture($picture);
              }
              $corporate->name = $name;
              $corporate->email = $email;
              $corporate->mobile = $mobile;
              $corporate->gender = $gender;
              $corporate->is_activated = 1;
              $corporate->is_approved = 1;
              $corporate->paypal_email = $request->paypal_email;
              $corporate->address = $address;


            if($request->id == ''){

            $subject = Helper::tr('corporate_welcome_title');
            $page = "emails.admin.welcome";
            $email_data['first_name'] = $corporate->name;
                $email_data['last_name'] = "";
                $email_data['password'] = $new_password;
                $email_data['email'] = $corporate->email;
            $email = $corporate->email;
            Helper::send_email($page,$subject,$email,$email_data);
            }

            $corporate->save();

            if($corporate)
            {
                return back()->with('flash_success', tr('admin_not_corporate'));
            }
            else
            {
                return back()->with('flash_error', tr('admin_not_error'));
            }
        }
    }

    public function edit_corporate(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->corporates !='' && $user->corporates !=0 && in_array(EDIT, explode(',', $user->corporates))){
            $corporate = Corporate::find($request->id);
           
            $service_types = ServiceType::all();
            return view('admin.add-corporate')->with('name', 'Edit corporate')->with('corporate',$corporate)->with('service_types',$service_types)
                                      ->withPage('corporates')->with('sub_page','view-corporate');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function corporate_approve(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->corporates !='' && $user->corporates !=0 && in_array(APPROVE, explode(',', $user->corporates))){
            $corporates = Corporate::orderBy('created_at' , 'asc')->get();
            $corporate = Corporate::find($request->id);
            $corporate->is_approved = $request->status;
            $corporate->save();
            if($request->status ==1)
            {
                $message = tr('admin_not_corporate_approve');
            }
            else
            {
                $message = tr('admin_not_corporate_decline');
            }
            return back()->with('flash_success', $message)->with('corporates',$corporates);
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function delete_corporate(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->corporates !='' && $user->corporates !=0 && in_array(DELET, explode(',', $user->corporates))){
            if($corporate = Corporate::find($request->id))
            {

                $corporate = Corporate::find($request->id)->delete();
            }

            if($corporate)
            {
                return back()->with('flash_success',tr('admin_not_corporate_del'));
            }
            else
            {
                return back()->with('flash_error',tr('admin_not_error'));
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function corporate_details(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->corporates !='' && $user->corporates !=0 && in_array(VIEW_DETAILS, explode(',', $user->corporates))){
            $corporate = Corporate::find($request->id);
            $corporate = Corporate::find($request->id);
          
            if($corporate) {

                return view('admin.corporate-details')->with('corporate' , $corporate)
                          ->withPage('corporates')->with('sub_page','view-corporate');
            } else {
                return back()->with('error' , "corporate details not found");
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    //-------------------------------------------------------

    // Call center manager Functions

    public function managers()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->call_center_managers !='' && $user->call_center_managers !=0 && in_array(VIEW_ALL, explode(',', $user->call_center_managers))){
            $corporates = CallCenterManager::orderBy('created_at' , 'desc')->get();

            return view('admin.managers')->with('corporates',$corporates)
                                          ->withPage('call_centers')->with('sub_page','view-manager');;
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_manager()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->call_center_managers !='' && $user->call_center_managers !=0 && in_array(ADD, explode(',', $user->call_center_managers))){
            $service_type = ServiceType::all();
            return view('admin.add-manager')->with('service_types',$service_type)->withPage('call_centers')->with('sub_page','add-manager');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_manager_process(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $mobile = $request->mobile;
        $gender = $request->gender;
        $picture = $request->file('picture');
        $address = $request->address;
        // echo "HI"; exit;
        if($request->id != '')
        {
            $validator = Validator::make(
                $request->all(),
                array(
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255',
                    'mobile' => 'required|digits_between:6,13',
                    'address' => 'required|max:300',

                )
            );
        }
        else
        {
            $validator = Validator::make(
                $request->all(),
                array(
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255',
                    'mobile' => 'required|digits_between:6,13',
                    'address' => 'required|max:300',
                    'picture' => 'required|mimes:jpeg,jpg,bmp,png',

                )
            );

            // echo "hi"; exit;
        }


        if($validator->fails())
        {
            
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {
               // echo "hi"; exit;
              if($request->id != '')
              {
                  // Edit corporate
                  $corporate = CallCenterManager::find($request->id);
                  if($picture != ''){
                  $corporate->picture = Helper::upload_picture($picture);
                  }
              }
              else
              {

                  //Add New corporate
                  $corporate = new CallCenterManager;
                  $new_password = time();
                  $new_password .= rand();
                  $new_password = sha1($new_password);
                  $new_password = substr($new_password, 0, 8);
                  $corporate->password = Hash::make($new_password);

                  $corporate->picture = Helper::upload_picture($picture);
                  session(['pass' => $corporate->password]);
              }

              $corporate->name = $name;
              $corporate->email = $email;
              $corporate->mobile = $mobile;
              $corporate->gender = $gender;
              $corporate->is_activated = 1;
              $corporate->is_approved = 1;
              $corporate->payment_mode = COD;
              $corporate->paypal_email = $request->paypal_email;
              $corporate->address = $address;



            if($request->id == ''){

            $subject = Helper::tr('manager_welcome_title');
            $page = "emails.admin.welcome";
            $email_data['first_name'] = $corporate->name;
                $email_data['last_name'] = "";
                $email_data['password'] = $new_password;
                $email_data['email'] = $corporate->email;
            $email = $corporate->email;
            \Log::info($email);
            Helper::send_email($page,$subject,$email,$email_data);
            \Log::info(Helper::send_email($page,$subject,$email,$email_data));
            }

            $corporate->save();

            if($corporate)
            {
                return back()->with('flash_success', tr('admin_not_manager'));
            }
            else
            {
                return back()->with('flash_error', tr('admin_not_error'));
            }
        }
    }

    public function edit_manager(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->call_center_managers !='' && $user->call_center_managers !=0 && in_array(EDIT, explode(',', $user->call_center_managers))){
            $corporate = CallCenterManager::find($request->id);
           
            $service_types = ServiceType::all();
            return view('admin.add-manager')->with('name', 'Edit manager')->with('corporate',$corporate)->with('service_types',$service_types)->withPage('call_centers')->with('sub_page','view-manager');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function manager_approve(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->call_center_managers !='' && $user->call_center_managers !=0 && in_array(APPROVE, explode(',', $user->call_center_managers))){
            $corporates = CallCenterManager::orderBy('created_at' , 'asc')->get();
            $corporate = CallCenterManager::find($request->id);
            $corporate->is_approved = $request->status;
            $corporate->save();
            if($request->status ==1)
            {
                $message = tr('admin_not_manager_approve');
            }
            else
            {
                $message = tr('admin_not_manager_decline');
            }
            return back()->with('flash_success', $message)->with('corporates',$corporates);
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function delete_manager(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->call_center_managers !='' && $user->call_center_managers !=0 && in_array(DELET, explode(',', $user->call_center_managers))){
            if($corporate = CallCenterManager::find($request->id))
            {

                $corporate = CallCenterManager::find($request->id)->delete();
            }

            if($corporate)
            {
                return back()->with('flash_success',tr('admin_not_manager_del'));
            }
            else
            {
                return back()->with('flash_error',tr('admin_not_error'));
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function manager_details(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->call_center_managers !='' && $user->call_center_managers !=0 && in_array(VIEW_DETAILS, explode(',', $user->call_center_managers))){
            $corporate = CallCenterManager::find($request->id);
          
            if($corporate) {

                return view('admin.manager-details')->with('corporate' , $corporate)
                          ->withPage('call_centers')->with('sub_page','view-manager');
            } else {
                return back()->with('error' , "manager details not found");
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

//--------------------------------------------------------------------------

// finding the near-by providers
    public function find_providers(Request $request) {
    $user = Admin::find(Auth::guard('admin')->user()->id);
    if($user->ride_requests_management !='' && $user->ride_requests_management !=0 && in_array(RE_ASSIGN, explode(',', $user->ride_requests_management))){
            //print_r(json_encode($request->all()));exit;
            Log::info('send_request'.print_r($request->all() ,true));

                if($request->type == "re_assign"){
                    $request = Requests::find($request->request_id);
                    $user = User::find($request->user_id);
                    $request->email = $user->email;
                }
                $user = User::where('email',trim($request->email))->first();

                //making COD by default if request is made by manager
                // if(Auth::guard('manager')->user()){
                //     $user->payment_mode = COD; 
                // }
                //making COD by default if request is made by manager
                     $user->payment_mode = COD; 

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
                                        'admin_id' => Auth::guard('admin')->user()->id,
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
                
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
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
      // print_r(json_encode($request));exit;

                $user = User::find($request->user_id);
                $request->email = $user->email;
        //print_r(json_encode(Requests::find($request->request_id)));exit;

            }
      $manager_id = 0;
      //$manager_id = Auth::guard('manager')->user()->id;
 //print_r(json_encode($manager_id));exit;   
      
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
//print_r(json_encode($user));exit;
//print_r(json_encode($user->latitude));exit;
            // Save the user location
            $user->latitude = $request->s_latitude;
            $user->longitude = $request->s_longitude;
            $user->save();
//exit;
            if(!$user->payment_mode) {
                Log::info('Payment Mode is not available');
                $response_array = array('success' => false , 'error' => Helper::get_error_message(134) , 'error_code' => 134);
            } else {
               // print_r(json_encode($user));exit;
//exit;
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
                                    $requests->manager_id = 0;//since the reassign is made by admin updatinf manager_id to 0 and adding the admin_id
                                    $requests->admin_id = Auth::guard('admin')->user()->id;
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



    //User Functions

    public function users()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->users !='' && $user->users !=0 && in_array(VIEW_ALL, explode(',', $user->users))){
            $user = User::orderBy('created_at' , 'desc')->get();

            return view('admin.users')->withPage('users')->with('sub_page','view-user')->with('users',$user);
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_user()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->users !='' && $user->users !=0 && in_array(ADD, explode(',', $user->users))){
            return view('admin.add-user')->withPage('users')->with('sub_page','add-user');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_user_process(Request $request)
    {
            $first_name = $request->first_name;
            $last_name = $request->last_name;
            $email = $request->email;
            $mobile = $request->mobile;
            $gender = $request->gender;
            $picture = $request->file('picture');
            $address = $request->address;

            if($request->id != '')
            {
                $validator = Validator::make(
                    $request->all(),
                    array(
                        'first_name' => 'required|max:255',
                        'last_name' => 'required|max:255',
                        'email' => 'required|email|max:255',
                        'mobile' => 'required|digits_between:6,13',
                        'address' => 'required|max:300',

                    )
                );
            }
            else
            {
                $validator = Validator::make(
                    $request->all(),
                    array(
                        'first_name' => 'required|max:255',
                        'last_name' => 'required|max:255',
                        'email' => 'required|email|max:255|unique:users,email',
                        'mobile' => 'required|digits_between:6,13',
                        'address' => 'required|max:300',
                        'picture' => 'required|mimes:jpeg,jpg,bmp,png',

                    )
                );
            }

        if($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {

                if($request->id != '')
                {
                    // Edit User
                    $user = User::find($request->id);
                    if($picture != ''){
                    $user->picture = Helper::upload_picture($picture);
                    }
                }
                else
                {
                    //Add New User
                    $user = new User;
                    $new_password = time();
                    $new_password .= rand();
                    $new_password = sha1($new_password);
                    $new_password = substr($new_password, 0, 8);
                    $user->password = Hash::make($new_password);
                    $user->is_registered = 1;
                    $user->picture = Helper::upload_picture($picture);
                }
                    $user->first_name = $first_name;
                    $user->last_name = $last_name;
                    $user->email = $email;
                    $user->mobile = $mobile;
                    $user->token = Helper::generate_token();
                    $user->token_expiry = Helper::generate_token_expiry();
                    $user->gender = $gender;
                    $user->is_activated = 1;
                    $user->is_approved = 1;
                    $user->payment_mode = COD;
                    $user->address = $address;
                    $user->timezone = "Asia/Kolkata";


                    if($request->id == ''){
                    $email_data['first_name'] = $user->first_name;
                    $email_data['last_name'] = $user->last_name;
                    $email_data['password'] = $new_password;
                    $email_data['email'] = $user->email;

                    $subject = Helper::tr('user_welcome_title');
                    $page = "emails.admin.welcome";
                    $email = $user->email;
                    Helper::send_email($page,$subject,$email,$email_data);
                    }

                    $user->save();

                if($user)
                {
                    return back()->with('flash_success', tr('admin_not_user'));
                }
                else
                {
                    return back()->with('flash_error', tr('admin_not_error'));
                }

            }
    }

    public function edit_user(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->users !='' && $user->users !=0 && in_array(EDIT, explode(',', $user->users))){
            $user = User::find($request->id);
            return view('admin.add-user')->with('name', 'Edit User')->with('user',$user)
                          ->withPage('users')->with('sub_page','view-user');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function delete_user(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->users !='' && $user->users !=0 && in_array(DELET, explode(',', $user->users))){
            if($user = User::find($request->id))
            {

                $user = User::find($request->id)->delete();
            }

            if($user)
            {
                return back()->with('flash_success',tr('admin_not_user_del'));
            }
            else
            {
                return back()->with('flash_error',tr('admin_not_error'));
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }


    //sub_admin  Functions

    public function sub_admins()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->sub_admins !='' && $user->sub_admins !=0 && in_array(VIEW_ALL, explode(',', $user->sub_admins))){
            $user = Admin::orderBy('created_at' , 'desc')->where('role',SUB_ADMIN)->get();

            return view('admin.sub_admins')->withPage('sub_admins')->with('sub_page','view-sub_admin')->with('users',$user);
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_sub_admin()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->sub_admins !='' && $user->sub_admins !=0 && in_array(ADD, explode(',', $user->sub_admins))){
            return view('admin.add-sub_admin')->withPage('sub_admins')->with('sub_page','add-sub_admin');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_sub_admin_process(Request $request)
    {
        //dd($request->all());
        $name = $request->name;
        $email = $request->email;
        $mobile = $request->mobile;
        $gender = $request->gender;
        $picture = $request->file('picture');
        $new_password = $request->password;
        //$address = $request->address;

        if($request->id != '')
        {
            $validator = Validator::make(
                $request->all(),
                array(
                    'name' => 'required|max:255',
                    //'first_name' => 'required|max:255',
                    //'last_name' => 'required|max:255',
                    'email' => 'required|email|max:255',
                    'mobile' => 'required|digits_between:6,13',
                    'password' => 'required',
                    //'address' => 'required|max:300',

                )
            );
        }
        else
        {
            $validator = Validator::make(
                $request->all(),
                array(
                    //'first_name' => 'required|max:255',
                    //'last_name' => 'required|max:255',
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:admins,email',
                    'password' => 'required',
                    'mobile' => 'required|digits_between:6,13',
                    //'address' => 'required|max:300',
                    'picture' => 'required|mimes:jpeg,jpg,bmp,png',

                )
            );
        }

        if($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {

            if($request->id != '')
            {
                // Edit sub-admin
                $user = Admin::find($request->id);
                if($picture != ''){
                    $user->picture = Helper::upload_picture($picture);
                }
                if($new_password != ''){
                    $user->password = Hash::make($new_password);
                }
            }
            else
            {
                //Add New sub-admin
                $user = new Admin;
                //$new_password = time();
                // $new_password .= rand();
                // $new_password = sha1($new_password);
                // $new_password = substr($new_password, 0, 8);
                $user->password = Hash::make($new_password);
                $user->picture = Helper::upload_picture($picture);
                $user->role = SUB_ADMIN;
            }
                //$user->first_name = $first_name;
                //$user->last_name = $last_name;
                $user->name = $name;
                $user->email = $email;
                $user->mobile = $mobile;
                $user->remember_token = Helper::generate_token();
                //$user->token = Helper::generate_token();
                //$user->token_expiry = Helper::generate_token_expiry();
                $user->gender = $gender;
                $user->is_activated = 1;
                //$user->is_approved = 1;
                //$user->payment_mode = 1;
                //$user->address = $address;


                // if($request->id == ''){
                // $email_data['name'] = $user->name;
                // //$email_data['first_name'] = $user->first_name;
                // //$email_data['last_name'] = $user->last_name;
                // $email_data['password'] = $new_password;
                // $email_data['email'] = $user->email;

                // $subject = Helper::tr('user_welcome_title');
                // $page = "emails.admin.welcome";
                // $email = $user->email;
                // Helper::send_email($page,$subject,$email,$email_data);
                // }
            if($request->has('dashboard')){
                $dashboard = rtrim(implode(',', $request->dashboard), ',');
                $user->dashboard = $dashboard;
                
            }else
            {
                $user->dashboard ="";
            }
            if($request->has('booking_stats')){
                $booking_stats = rtrim(implode(',', $request->booking_stats), ',');
                $user->booking_stats = $booking_stats;
            }else
            {
                $user->booking_stats ="";
            }
            if($request->has('driver_availability_stats')){
                $driver_availability_stats = rtrim(implode(',', $request->driver_availability_stats), ',');
                $user->driver_availability_stats = $driver_availability_stats;
            }else
            {
                $user->driver_availability_stats ="";
            }
            if($request->has('corporates')){
                $corporates = rtrim(implode(',', $request->corporates), ',');
                // print_r($corporates); exit;
                $user->corporates = $corporates;
            }else
            {
                $user->corporates ="";
            }
            if($request->has('call_center_managers')){
                $call_center_managers = rtrim(implode(',', $request->call_center_managers), ',');
                $user->call_center_managers = $call_center_managers;
            }else
            {
                $user->call_center_managers ="";
            }
            if($request->has('users')){
                $users = rtrim(implode(',', $request->users), ',');
                $user->users = $users;
            }else
            {
                $user->users ="";
            }
            if($request->has('sub_admins')){
                $sub_admins = rtrim(implode(',', $request->sub_admins), ',');
                $user->sub_admins = $sub_admins;
            }else
            {
                $user->sub_admins ="";
            }
            if($request->has('providers')){
                $providers = rtrim(implode(',', $request->providers), ',');
                $user->providers = $providers;
            }else
            {
                $user->providers ="";
            }
            if($request->has('ride_requests_management')){
                $ride_requests_management = rtrim(implode(',', $request->ride_requests_management), ',');
                $user->ride_requests_management = $ride_requests_management;
            }else
            {
                $user->ride_requests_management ="";
            }
            if($request->has('vehicle_types')){
                $vehicle_types = rtrim(implode(',', $request->vehicle_types), ',');
                $user->vehicle_types = $vehicle_types;
            }else
            {
                $user->vehicle_types ="";
            }
            if($request->has('promo_codes')){
                $promo_codes = rtrim(implode(',', $request->promo_codes), ',');
                $user->promo_codes = $promo_codes;
            }else
            {
                $user->promo_codes ="";
            }
            if($request->has('rental_management')){
                $rental_management = rtrim(implode(',', $request->rental_management), ',');
                $user->rental_management = $rental_management;
            }else
            {
                $user->rental_management ="";
            }
            if($request->has('airport_details')){
                $airport_details = rtrim(implode(',', $request->airport_details), ',');
                $user->airport_details = $airport_details;
            }else
            {
                $user->airport_details ="";
            }
            if($request->has('destination_details')){
                $destination_details = rtrim(implode(',', $request->destination_details), ',');
                $user->destination_details = $destination_details;
            }else
            {
                $user->destination_details ="";
            }
            if($request->has('pricing_management')){
                $pricing_management = rtrim(implode(',', $request->pricing_management), ',');
                $user->pricing_management = $pricing_management;
            }else
            {
                $user->pricing_management ="";
            }
            if($request->has('provider_ratings')){
                $provider_ratings = rtrim(implode(',', $request->provider_ratings), ',');
                $user->provider_ratings = $provider_ratings;
            }else
            {
                $user->provider_ratings ="";
            }
            if($request->has('user_ratings')){
                $user_ratings = rtrim(implode(',', $request->user_ratings), ',');
                $user->user_ratings = $user_ratings;
            }else
            {
                $user->user_ratings ="";
            }
            if($request->has('documents_management')){
                $documents_management = rtrim(implode(',', $request->documents_management), ',');
                $user->documents_management = $documents_management;
            }else
            {
                $user->documents_management ="";
            }
            if($request->has('currency_management')){
                $currency_management = rtrim(implode(',', $request->currency_management), ',');
                $user->currency_management = $currency_management;
            }else
            {
                $user->currency_management ="";
            }
            if($request->has('transactions')){
                $transactions = rtrim(implode(',', $request->transactions), ',');
                $user->transactions = $transactions;
            }else
            {
                $user->transactions ="";
            }
            if($request->has('push_notifications')){
                $push_notifications = rtrim(implode(',', $request->push_notifications), ',');
                $user->push_notifications = $push_notifications;
            }else
            {
                $user->push_notifications ="";
            }
            if($request->has('settings')){
                $settings = rtrim(implode(',', $request->settings), ',');
                $user->settings = $settings;
            }else
            {
                $user->settings ="";
            }
            if($request->has('ads_management')){
                $ads_management = rtrim(implode(',', $request->ads_management), ',');
                $user->ads_management = $ads_management;
            }else
            {
                $user->ads_management ="";
            }

            $user->save();

            if($user)
            {
                return back()->with('flash_success', tr('admin_not_user'));
            }
            else
            {
                return back()->with('flash_error', tr('admin_not_error'));
            }

        }
    }

    public function edit_sub_admin(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->sub_admins !='' && $user->sub_admins !=0 && in_array(EDIT, explode(',', $user->sub_admins))){
            $user = Admin::find($request->id);
            return view('admin.add-sub_admin')->with('name', 'Edit sub_admin')->with('user',$user)
                          ->withPage('sub_admins')->with('sub_page','view-sub_admin');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function sub_admin_details(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->sub_admins !='' && $user->sub_admins !=0 && in_array(VIEW_DETAILS, explode(',', $user->sub_admins))){
            $user = Admin::find($request->id);
            //$avg_rev = UserRating::where('user_id',$request->id)->avg('rating');

            if($user) {
              if($request->option !='')
                // return view('admin.user-details')->with('user' , $user)->with('review',$avg_rev)
                //           ->withPage('users')->with('sub_page','view-user')->with('name',$request->option);
                return view('admin.sub_admin-details')->with('user' , $user)->withPage('sub_admins')->with('sub_page','view-sub_admin')->with('name',$request->option);
              else
                 //return view('admin.user-details')->with('user' , $user)->with('review',$avg_rev)
                //             ->withPage('maps')->with('sub_page','user-map');
                return view('admin.sub_admin-details')->with('user' , $user)->withPage('sub_admins')->with('sub_page','view-sub_admin');

            } else {
                return back()->with('error' , "Sub Admin details not found");
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function delete_sub_admin(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->sub_admins !='' && $user->sub_admins !=0 && in_array(DELET, explode(',', $user->sub_admins))){
            if($user = Admin::find($request->id))
            {

                $user = Admin::find($request->id)->delete();
            }

            if($user)
            {
                return back()->with('flash_success',tr('admin_not_sub_admin_del'));
            }
            else
            {
                return back()->with('flash_error',tr('admin_not_error'));
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }



    //Provider Functions

    public function providers()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->providers !='' && $user->providers !=0 && in_array(VIEW_ALL, explode(',', $user->providers))){
            $subQuery = DB::table('requests')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('confirmed_provider = providers.id and status != 0');

            $subQuery1 = DB::table('requests')
                    ->select(DB::raw('count(*)'))
                    ->whereRaw('confirmed_provider = providers.id and status in (1,2,3,4,5)');

            $subQuery2 = DB::table('request_payments')->leftJoin('requests', 'request_payments.request_id', '=', 'requests.id')
                ->select(DB::raw('sum(provider_earnings)'))
                ->whereRaw('confirmed_provider = providers.id and requests.status='.REQUEST_COMPLETED);

            $subQuery3 = DB::table('request_payments')->leftJoin('requests', 'request_payments.request_id', '=', 'requests.id')
                ->select(DB::raw('sum(total)'))
                ->whereRaw('confirmed_provider = providers.id and requests.status='.REQUEST_COMPLETED);

            $providers = DB::table('providers')
                    ->select('providers.*', DB::raw("(" . $subQuery->toSql() . ") as 'total_requests'"), DB::raw("(" . $subQuery1->toSql() . ") as 'accepted_requests'"), DB::raw("(" . $subQuery2->toSql() . ") as 'total_provider_earnings'"), DB::raw("(" . $subQuery3->toSql() . ") as 'total_request_earnings'"))
                    ->orderBy('providers.id', 'DESC')
                    ->get();

            return view('admin.providers')->with('providers',$providers)
                                          ->withPage('providers')->with('sub_page','view-provider');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_provider()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->providers !='' && $user->providers !=0 && in_array(ADD, explode(',', $user->providers))){
            $service_type = ServiceType::all();
            $corporates = Corporate::all();
            return view('admin.add-provider')->with('service_types',$service_type)->withPage('providers')->with('sub_page','add-provider')->with('corporates',$corporates);
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_provider_process(Request $request)
    {
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $email = $request->email;
        $mobile = $request->mobile;
        $gender = $request->gender;
        $picture = $request->file('picture');
        $address = $request->address;
        $new_password = $request->password;
        $password_confirmation = $request->password_confirmation;
        $plate_no = $request->plate_no;
        $model = $request->model;
        $color = $request->color;
        $car_image = $request->file('car_image');

        if($request->id != '')
        {
            $validator = Validator::make(
                $request->all(),
                array(
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'email' => 'required|email|max:255',
                    'password' => 'min:6|confirmed',
                    'mobile' => 'required|digits_between:6,13',
                    'address' => 'required|max:300',
                    'plate_no' => 'required',
                    'model' => 'required',
                    'color' => 'required',
                )
            );
        }
        else
        {
            $validator = Validator::make(
                $request->all(),
                array(
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:providers,email',
                    'password' => 'required|min:6|confirmed',
                    'mobile' => 'required|digits_between:6,13',
                    'address' => 'required|max:300',
                    'plate_no' => 'required',
                    'model' => 'required',
                    'color' => 'required',
                    'picture' => 'mimes:jpeg,jpg,bmp,png',
                    'car_image' => 'mimes:jpeg,jpg,bmp,png',

                )
            );
        }

        if($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {
              if($request->id != '')
              {
                  // Edit Provider
                  $provider = Provider::find($request->id);
                  if($new_password!=""){
                    $provider->password = Hash::make($new_password);
                  }
                  if($picture != ''){
                  $provider->picture = Helper::upload_picture($picture);
                  }
                  if($car_image != ''){
                  $provider->car_image = Helper::upload_picture($car_image);
                  }
              }
              else
              {
                  //Add New Provider
                  $provider = new Provider;
                  // $new_password = time();
                  // $new_password .= rand();
                  // $new_password = sha1($new_password);
                  // $new_password = substr($new_password, 0, 8);
                  $provider->password = Hash::make($new_password);
                  $provider->picture = Helper::upload_picture($picture);
                  $provider->car_image = Helper::upload_picture($car_image);
              }
              $provider->first_name = $first_name;
              $provider->last_name = $last_name;
              $provider->email = $email;
              $provider->mobile = $mobile;
              $provider->token = Helper::generate_token();
              $provider->token_expiry = Helper::generate_token_expiry();
              $provider->gender = $gender;
              $provider->is_activated = 1;
              $provider->is_approved = 1;
              $provider->paypal_email = $request->paypal_email;
              $provider->address = $address;
              $provider->plate_no = $plate_no;
              $provider->model = $model;
              $provider->color = $color;
              $provider->corporate_id = $request->corporate;
              $provider->timezone = "Asia/Kolkata";


              if($request->id == ''){

                $subject = Helper::tr('provider_welcome_title');
                $page = "emails.admin.welcome";
                $email_data['first_name'] = $provider->first_name;
                    $email_data['last_name'] = $provider->last_name;
                    $email_data['password'] = $new_password;
                    $email_data['email'] = $provider->email;
                $email = $provider->email;
                Helper::send_email($page,$subject,$email,$email_data);
                }

               $provider->save();

               if($provider) {

                    if($request->has('service_type')) {

                        $check_provider_service = ProviderService::where('provider_id' , $provider->id)
                                                ->first();

                        if(!$check_provider_service) {
                            $provider_service = new ProviderService;
                        } else {
                            $provider_service = $check_provider_service;
                        }

                        $provider_service->provider_id = $provider->id;
                        $provider_service->service_type_id = $request->service_type;
                        $provider_service->is_available = DEFAULT_TRUE;
                        $provider_service->save();
                    }
                }

                if($provider)
                {
                    return back()->with('flash_success', tr('admin_not_provider'));
                }
                else
                {
                    return back()->with('flash_error', tr('admin_not_error'));
                }
            }
    }

    public function edit_provider(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->providers !='' && $user->providers !=0 && in_array(EDIT, explode(',', $user->providers))){
            $provider = Provider::find($request->id);
            $corporates = Corporate::all();
            $check_provider_service = ProviderService::where('provider_id' , $request->id)->first();
            if($check_provider_service)
                $provider_type = $check_provider_service->service_type_id;
            else
                $provider_type = "";
            $service_types = ServiceType::all();
            return view('admin.add-provider')->with('name', 'Edit Provider')->with('provider_type',$provider_type)->with('provider',$provider)->with('service_types',$service_types)
                                      ->withPage('providers')->with('sub_page','view-provider')
                                      ->with('corporates',$corporates);
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function provider_documents(Request $request) 
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->providers !='' && $user->providers !=0 && in_array(VIEW_DOCUMENTS, explode(',', $user->providers))){
            $provider_id = $request->id;
            $provider = Provider::find($provider_id);
            $documents = Document::all();
            $provider_document = DB::table('provider_documents')
                                ->leftJoin('documents', 'provider_documents.document_id', '=', 'documents.id')
                                ->select('provider_documents.*', 'documents.name as document_name')
                                ->where('provider_id', $provider_id)->get();


            return view('admin.provider-document')
                            ->with('provider', $provider)
                            ->with('document', $documents)
                            ->with('documents', $provider_document)
                            ->withPage('providers')->with('sub_page','view-provider');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function Provider_approve(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->providers !='' && $user->providers !=0 && in_array(APPROVE, explode(',', $user->providers))){
            $providers = Provider::orderBy('created_at' , 'asc')->get();
            $provider = Provider::find($request->id);
            $provider->is_approved = $request->status;
            $provider->save();
            if($request->status ==1)
            {
                $message = tr('admin_not_provider_approve');
            }
            else
            {
                $message = tr('admin_not_provider_decline');
            }
            return back()->with('flash_success', $message)->with('providers',$providers);
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function delete_provider(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->providers !='' && $user->providers !=0 && in_array(DELET, explode(',', $user->providers))){
            if($provider = Provider::find($request->id))
            {

                $provider = Provider::find($request->id)->delete();
            }

            if($provider)
            {
                return back()->with('flash_success',tr('admin_not_provider_del'));
            }
            else
            {
                return back()->with('flash_error',tr('admin_not_error'));
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function settings()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->settings !='' && $user->settings !=0 && in_array(VIEW_ALL, explode(',', $user->settings))){
            $settings = Settings::all();
            switch (Setting::get('currency')) {
                case '$':
                    $symbol = '$';
                    $currency = 'US Dollar (USD)';
                    break;

                case '₹':
                    $symbol = '₹';
                    $currency = 'Indian Rupee (INR)';
                    break;
                case 'د.ك':
                    $symbol = 'د.ك';
                    $currency = 'Kuwaiti Dinar (KWD)';
                    break;
                case 'د.ب':
                    $symbol = 'د.ب';
                    $currency = 'Bahraini Dinar (BHD)';
                    break;
                case '﷼':
                    $symbol = '﷼';
                    $currency = 'Omani Rial (OMR)';
                    break;
                case '£':
                    $symbol = '£';
                    $currency = 'Euro (EUR)';
                    break;
                case '€':
                    $symbol = '€';
                    $currency = 'British Pound (GBP)';
                    break;
                case 'ل.د':
                    $symbol = 'ل.د';
                    $currency = 'Libyan Dinar (LYD)';
                    break;
                case 'B$':
                    $symbol = 'B$';
                    $currency = 'Bruneian Dollar (BND)';
                    break;
                case 'S$':
                    $symbol = 'S$';
                    $currency = 'Singapore Dollar (SGD)';
                    break;
                case 'AU$':
                    $symbol = 'AU$';
                    $currency = 'Australian Dollar (AUD)';
                    break;
                case 'CHF':
                    $symbol = 'CHF';
                    $currency = 'Swiss Franc (CHF)';
                    break;
                case 'TRX':
                    $symbol = 'TRX';
                    $currency = 'Tron Coin (TRX)';
                    break;
                default:
                    $symbol = '$';
                    $currency = 'US Dollar (USD)';
                    break;
            }
            return view('admin.settings')->with('symbol',$symbol)->with('currency',$currency)->withPage('settings')->with('sub_page','');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }


    public function settings_process(Request $request)
    {
        $settings = Settings::all();
        foreach ($settings as $setting) {
            $key = $setting->key;

                $temp_setting = Settings::find($setting->id);

                if($temp_setting->key == 'site_icon'){
                    $site_icon = $request->file('site_icon');
                    if($site_icon == null)
                    {

                        $icon = $temp_setting->value;
                    }
                    else
                    {

                        $icon = Helper::upload_picture($site_icon);

                    }
                    $temp_setting->value = $icon;
                    $temp_setting->save();
                }

               else if($temp_setting->key == 'site_logo'){
                    $picture = $request->file('picture');
                    if($picture == null){
                        $logo = $temp_setting->value;
                    // $logo = url("/images/logo.png");
                    }
                    else
                    {
                        $logo = Helper::upload_picture($picture);
                    }
                    $temp_setting->value = $logo;
                    $temp_setting->save();
                }
                else if($temp_setting->key == 'mail_logo'){
                    $picture = $request->file('email_logo');
                    if($picture == null){
                    $logo = $temp_setting->value;
                    }
                    else
                    {
                        $logo = Helper::upload_picture($picture);
                    }
                    $temp_setting->value = $logo;
                    $temp_setting->save();
                }

                 else if($temp_setting->key == 'wallet_bay_key'){
                      $temp_setting->value = $request->wallet_bay_key;
                      $temp_setting->save();
                  }
                  else if($temp_setting->key == 'wallet_url'){
                      $temp_setting->value = $request->wallet_url;
                      $temp_setting->save();
                  }
                /* else if($temp_setting->key == 'tron_address_hex'){
                    $temp_setting->value = $request->tron_address_hex;
                    $temp_setting->save();
                }
                else if($temp_setting->key == 'tron_address_base58'){
                    $temp_setting->value = $request->tron_address_base58;
                    $temp_setting->save();
                }
                else if($temp_setting->key == 'tron_private_key'){
                    $temp_setting->value = $request->tron_private_key;
                    $temp_setting->save();
                }
                else if($temp_setting->key == 'tron_api_url'){
                    $temp_setting->value = $request->tron_api_url;
                    $temp_setting->save();
                } */

                 //  else if($temp_setting->key == 'card'){
                 //      if($request->$key==1)
                 //      {
                 //          $temp_setting->value   = 1;

                 //      }
                 //      else
                 //      {
                 //          $temp_setting->value = 0;
                 //      }
                 //      $temp_setting->save();
                 //  }
                 // else if($temp_setting->key == 'paypal'){
                 //      if($request->$key==1)
                 //      {
                 //          $temp_setting->value   = 1;
                 //      }
                 //      else
                 //      {
                 //          $temp_setting->value = 0;
                 //      }
                 //      $temp_setting->save();
                 //  }
                  else if($temp_setting->key == 'manual_request'){
                      if($request->$key==1)
                      {
                          $temp_setting->value   = 1;
                      }
                      else
                      {
                          $temp_setting->value = 0;
                      }
                      $temp_setting->save();
                  }
                  else if($temp_setting->key == 'cancellation_fine'){
                      $temp_setting->value = $request->cancellation_fine;
                      $temp_setting->save();
                  }
                  else if($request->$key!=''){
                $temp_setting->value = $request->$key;
                $temp_setting->save();
                }
            }
        return back()->with('setting', $settings)->with('flash_success','Settings Updated Successfully');
    }

    //Documents

    public function documents()
    {
        $document = Document::orderBy('created_at' , 'asc')->get();
        return view('admin.documents')->with('documents',$document)
                    ->withPage('documents')->with('sub_page','view-document');
    }

    public function document_edit(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->documents_management !='' && $user->documents_management !=0 && in_array(VIEW_ALL, explode(',', $user->documents_management))){
            $document = Document::find($request->id);
            return view('admin.add-documents')->with('name', 'Edit Document')->with('document',$document)
                        ->withPage('documents')->with('sub_page','view-document');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_document()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->documents_management !='' && $user->documents_management !=0 && in_array(EDIT, explode(',', $user->documents_management))){
            return view('admin.add-documents')->withPage('documents')->with('sub_page','add-document');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_document_process(Request $request)
    {

                $validator = Validator::make(
                    $request->all(),
                    array(
                        'document_name' => 'required|max:255',

                    )
                );
            if($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {
            if($request->id != '')
            {
                $document = Document::find($request->id);
                $message = tr('admin_not_doc_updated');
            }
            else
            {
                $document = new Document;
                $message = tr('admin_not_doc');
            }
                $document->name = $request->document_name;
                $document->save();

        if($document)
        {
            return back()->with('flash_success',$message);
        }
        else
        {
            return back()->with('flash_error',tr('admin_not_error'));
        }
        }
    }

    public function delete_document(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->documents_management !='' && $user->documents_management !=0 && in_array(DELET, explode(',', $user->documents_management))){
            $document = Document::find($request->id)->delete();

            if($document)
            {
                return back()->with('flash_success',tr('admin_not_doc_del'));
            }
            else
            {
                return back()->with('flash_error',tr('admin_not_error'));
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }
    
    //Currency

    public function currency()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->currency_management !='' && $user->currency_management !=0 && in_array(VIEW_ALL, explode(',', $user->currency_management))){
            $currency = Currency::orderBy('created_at' , 'asc')->get();
            return view('admin.currency')->with('currency',$currency)
                        ->withPage('currency')->with('sub_page','view-currency');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function currency_edit(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->currency_management !='' && $user->currency_management !=0 && in_array(EDIT, explode(',', $user->currency_management))){
            $currency = Currency::find($request->id);
            return view('admin.add-currency')->with('name', 'Edit Currency')->with('currency',$currency)
                        ->withPage('currency')->with('sub_page','view-currency');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_currency()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->currency_management !='' && $user->currency_management !=0 && in_array(ADD, explode(',', $user->currency_management))){
            return view('admin.add-currency')->withPage('currency')->with('sub_page','add-currency');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_currency_process(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'currency_name' => 'required',
                'currency_value' => 'required|numeric'

            )
        );
            if($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {
            if($request->id != '')
            {
                $currency = Currency::find($request->id);
                $message = tr('admin_not_currency_updated');
            }
            else
            {
                $currency = new Currency;
                $message = tr('admin_not_currency');
            }
                $currency->currency_name = $request->currency_name;
                $currency->currency_value = $request->currency_value;
                $currency->save();

        if($currency)
        {
            return back()->with('flash_success',$message);
        }
        else
        {
            return back()->with('flash_error',tr('admin_not_error'));
        }
        }
    }

    public function delete_currency(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->currency_management !='' && $user->currency_management !=0 && in_array(DELET, explode(',', $user->currency_management))){
            $document = Currency::find($request->id)->delete();

            if($document)
            {
                return back()->with('flash_success',tr('admin_not_currency_del'));
            }
            else
            {
                return back()->with('flash_error',tr('admin_not_error'));
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    //Cancellation Reasons

    public function cancellation_reasons()
    {
        $result = CancellationReasons::orderBy('created_at' , 'asc')->get();
        return view('admin.cancellation_reasons')->with('result',$result)
                    ->withPage('cancellation_reasons')->with('sub_page','view-reasons');
    }

    public function cancellation_reason_edit(Request $request)
    {
        $result = CancellationReasons::find($request->id);
        return view('admin.add_cancellation_reasons')->with('name', 'Edit Cancellation Reason')->with('result',$result)
                    ->withPage('cancellation_reasons')->with('sub_page','view-reasons');
    }

    public function add_cancellation_reason()
    {
        return view('admin.add_cancellation_reasons')->withPage('cancellation_reasons')->with('sub_page','add-reasons');
    }

    public function add_cancellation_reason_process(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            array(
                'cancel_reason' => 'required',
                'cancel_fee' => 'required|numeric'
            )
        );
            if($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {
            if($request->id != '')
            {
                $currency = CancellationReasons::find($request->id);
                $message = tr('admin_cancel_reason_updated');
            }
            else
            {
                $currency = new CancellationReasons;
                $message = tr('admin_cancel_reason_created');
            }
                $currency->cancel_reason = $request->cancel_reason;
                $currency->cancel_fee = $request->cancel_fee;
                $currency->save();

        if($currency)
        {
            return back()->with('flash_success',$message);
        }
        else
        {
            return back()->with('flash_error',tr('admin_not_error'));
        }
        }
    }

    public function delete_cancellation_reason(Request $request)
    {
        $document = CancellationReasons::find($request->id)->delete();
        if($document)
        {
            return back()->with('flash_success',tr('admin_cancel_reason_deleted'));
        }
        else
        {
            return back()->with('flash_error',tr('admin_not_error'));
        }
    }

    // Advertisement 

    public function ads()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->ads_management !='' && $user->ads_management !=0 && in_array(VIEW_ALL, explode(',', $user->ads_management))){
            $ads = Advertisement::orderBy('created_at' , 'asc')->get();
            return view('admin.ads')->with('ads',$ads)
                        ->withPage('ads')->with('sub_page','view-ads');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function ads_edit(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->ads_management !='' && $user->ads_management !=0 && in_array(EDIT, explode(',', $user->ads_management))){
            $ads = Advertisement::find($request->id);
            return view('admin.add-ads')->with('name', 'Edit Ads')->with('ads',$ads)
                        ->withPage('ads')->with('sub_page','view-ads');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_ads()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->ads_management !='' && $user->ads_management !=0 && in_array(ADD, explode(',', $user->ads_management))){
            return view('admin.add-ads')->withPage('ads')->with('sub_page','add-ads');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_ads_process(Request $request)
    {
        if($request->id != '')
        {
            $validator = Validator::make(
                $request->all(),
                array(
                'picture' => 'mimes:jpeg,jpg,bmp,png',
            )
            );
        }
        else
        {
            $validator = Validator::make(
                $request->all(),
                array(
                'picture' => 'required|mimes:jpeg,jpg,bmp,png',
                'description' => 'required',
                'url' => 'required',

                )
            );
        }
        if($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {
            //if($request->has('picture')){
                $picture = $request->file('picture');
            //}
            // if($request->has('url')){
            //     $url = $request->url;
            // }
            if($request->id != '')
            {
                $ads = Advertisement::find($request->id);
                if($picture != ''){
                $ads->picture = Helper::upload_picture($picture);
                }
            }
            else
            {
                $ads = new Advertisement;
                $ads->picture = Helper::upload_picture($picture);
            }
                $ads->url = $request->url;
                $ads->description = $request->description;
                $ads->save();

        if($ads)
        {
            return back()->with('flash_success',"Advertisement added successfully");
        }
        else
        {
            return back()->with('flash_error',"tr('admin_not_error')");
        }
        }
    }

    public function delete_ads(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->ads_management !='' && $user->ads_management !=0 && in_array(DELET, explode(',', $user->ads_management))){
            $ads = Advertisement::find($request->id)->delete();

            if($ads)
            {
                return back()->with('flash_success',tr('admin_not_ads_del'));
            }
            else
            {
                return back()->with('flash_error',tr('admin_not_error'));
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    //Service Types

    public function service_types()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->vehicle_types !='' && $user->vehicle_types !=0 && in_array(VIEW_ALL, explode(',', $user->vehicle_types))){
            $service = ServiceType::orderBy('order' , 'asc')->get();
            return view('admin.service_types')->with('services',$service)->withPage('service_types')->with('sub_page','view-service');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function edit_service(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->vehicle_types !='' && $user->vehicle_types !=0 && in_array(EDIT, explode(',', $user->vehicle_types))){
            $service = ServiceType::find($request->id);
            return view('admin.add_service_types')->with('name', 'Edit Service Types')->with('service',$service)
                        ->withPage('service_types')->with('sub_page','view-service');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_service_type()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->vehicle_types !='' && $user->vehicle_types !=0 && in_array(ADD, explode(',', $user->vehicle_types))){
            return view('admin.add_service_types')->withPage('service_types')->with('sub_page','add-service');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_service_process(Request $request)
    {
      $picture = $request->file('picture');
      if($request->id != '')
      {
          $validator = Validator::make(
              $request->all(),
              array(
                  'service_name' => 'required|max:255',
                  'number_seat' => 'required|max:10',
                  'base_fare' => 'required',
                  'min_fare' => 'required',
                  'tax_fee' => 'required',
                  'booking_fee' => 'required',
                  'price_per_min' => 'required',
                  'price_per_unit_distance' => 'required',
                  'distance_unit' => 'required|in:'.UNIT_DISTANCE,

              )
          );
      }
      else {
        $validator = Validator::make(
            $request->all(),
            array(
                'service_name' => 'required|max:255',
                // 'provider_name' => 'required|max:255',
                'base_fare' => 'required',
                'min_fare' => 'required',
                'tax_fee' => 'required',
                'booking_fee' => 'required',
                'number_seat' => 'required|max:10',
                'price_per_min' => 'required',
                'price_per_unit_distance' => 'required',
                'distance_unit' => 'required|in:'.UNIT_DISTANCE,
                'picture' => 'required|mimes:jpeg,jpg,bmp,png',

            )
        );
      }
        if($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {
            if($request->id != '')
            {
                $service = ServiceType::find($request->id);
                if($picture != ''){
                $service->picture = Helper::upload_picture($picture);
                }
                $message = tr('admin_not_st_updated');
            }
            else
            {
                $service = new ServiceType;
                $service->picture = Helper::upload_picture($picture);
                $message = tr('admin_not_st');

            }
            if ($request->is_default == 1) {
            ServiceType::where('status', 1)->update(array('status' => 0));
            $service->status = 1;
            }
            else
            {
                $service->status = 0;
            }
            $service->name = $request->service_name;
            // $service->provider_name = $request->provider_name;
            $service->number_seat = $request->number_seat;
            $service->price_per_min = $request->price_per_min;
            $service->price_per_unit_distance = $request->price_per_unit_distance;
            $service->distance_unit = $request->distance_unit;
            $service->base_fare = $request->base_fare;
            $service->min_fare = $request->min_fare;
            $service->tax_fee = $request->tax_fee;
            $service->booking_fee = $request->booking_fee;
            $service->save();

        if($service)
        {
            return back()->with('flash_success',$message);
        }
        else
        {
            return back()->with('flash_error',tr('admin_not_error'));
        }
        }
    }

    public function delete_service(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->vehicle_types !='' && $user->vehicle_types !=0 && in_array(DELET, explode(',', $user->vehicle_types))){
            $service = ServiceType::find($request->id)->delete();

            if($service)
            {
                return back()->with('flash_success',tr('admin_not_st_del'));
            }
            else
            {
                return back()->with('flash_error',"Something went Wrong");
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }


    //Hourly package Types

    public function hourly_packages()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->rental_management !='' && $user->rental_management !=0 && in_array(VIEW_ALL, explode(',', $user->rental_management))){
            $hourly_package = DB::table('hourly_packages')->leftJoin('service_types','hourly_packages.car_type_id','=','service_types.id')
                            ->select('hourly_packages.id','hourly_packages.distance','hourly_packages.price',
                                'hourly_packages.number_hours','hourly_packages.car_type_id','service_types.name')->get();
            return view('admin.hourly_packages')->with('hourly_packages',$hourly_package)->withPage('hourly_packages')->with('sub_page','view-hourly_package');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function edit_hourly_package(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->rental_management !='' && $user->rental_management !=0 && in_array(EDIT, explode(',', $user->rental_management))){
            $hourly_package = HourlyPackage::find($request->id);
            if($hourly_package)
                $provider_type = $hourly_package->car_type_id;
            else
                $provider_type = "";
            $service_type = ServiceType::all();
            return view('admin.add_hourly_package')->with('name', 'Edit Hourly Package')->with('hourly_package',$hourly_package)
                        ->withPage('hourly_packages')->with('sub_page','view-hourly_package')->with('provider_type',$provider_type)
                        ->with('service_types',$service_type);
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_hourly_package()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->rental_management !='' && $user->rental_management !=0 && in_array(ADD, explode(',', $user->rental_management))){
            $service_type = ServiceType::all();
            return view('admin.add_hourly_package')->withPage('hourly_packages')->with('sub_page','add-hourly_package')->with('service_types',$service_type);
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_hourly_package_process(Request $request)
    {
      if($request->id != '')
      {
          $validator = Validator::make(
              $request->all(),
              array(
                  'number_hours' => 'required|max:255',
                  'price' => 'required|max:10',
                  'distance' => 'required',
                  'service_type' => 'required',

              )
          );
      }
      else {
        $validator = Validator::make(
            $request->all(),
            array(
                'number_hours' => 'required|max:255',
                'price' => 'required|max:10',
                'distance' => 'required',
                'service_type' => 'required',
            )
        );
      }
        if($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {
            if($request->id != '')
            {
                $hourly_package = HourlyPackage::find($request->id);
                
                $message = tr('admin_not_st_updated');
            }
            else
            {
                $hourly_package = new HourlyPackage;
                $message = tr('admin_not_st');

            }

            $hourly_package->number_hours = $request->number_hours;
            $hourly_package->price = $request->price;
            $hourly_package->distance = $request->distance;
            $hourly_package->car_type_id = $request->service_type;
            $hourly_package->save();

        if($hourly_package)
        {
            return back()->with('flash_success',$message);
        }
        else
        {
            return back()->with('flash_error',tr('admin_not_error'));
        }
        }
    }

    public function delete_hourly_package(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->rental_management !='' && $user->rental_management !=0 && in_array(DELET, explode(',', $user->rental_management))){

            $hourly_package = HourlyPackage::find($request->id)->delete();

            if($hourly_package)
            {
                return back()->with('flash_success',tr('admin_not_st_del'));
            }
            else
            {
                return back()->with('flash_error',"Something went Wrong");
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }


    //Airport 

    // Airport Details

    public function airport_details()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->airport_details !='' && $user->airport_details !=0 && in_array(VIEW_ALL, explode(',', $user->airport_details))){
            $airport_detail = AirportDetail::all();
            return view('admin.airport.airport_details')->with('airport_details',$airport_detail)->withPage('airport')->with('sub_page','airport-details');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function edit_airport_detail(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->airport_details !='' && $user->airport_details !=0 && in_array(EDIT, explode(',', $user->airport_details))){
            $airport_detail = AirportDetail::find($request->id);
            // $key = "AIzaSyAyw6xHTxm2Ym4yRZmzfOntxYcx-_M5tfg";
            $key = Helper::getKey();
            return view('admin.airport.add_airport_detail')->with('name', 'Edit Airport Details')->with('airport_detail',$airport_detail)
                        ->withPage('airport')->with('sub_page','airport-detail')
                        ->with('key', $key);
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_airport_detail()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->airport_details !='' && $user->airport_details !=0 && in_array(ADD, explode(',', $user->airport_details))){
            $key = Helper::getKey();
            // echo "string".$key; exit;
            return view('admin.airport.add_airport_detail')->withKey($key)->withPage('airport')->with('sub_page','airport-detail');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_airport_detail_process(Request $request)
    {
        $validator = Validator::make(
          $request->all(),
          array(
              'name' => 'required|max:255',
              'zipcode' => 'required|max:255',
            )
        );
  
        if($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {
            if($request->id != '')
            {
                $airport_detail = AirportDetail::find($request->id);
                
                $message = tr('admin_not_st_updated');
            }
            else
            {
                $airport_detail = new AirportDetail;
                $message = tr('admin_not_st');

            }

            $airport_detail->name = $request->name;
            $airport_detail->latitude = $request->latitude;
            $airport_detail->longitude = $request->longitude;
            $airport_detail->zipcode = $request->zipcode;
            $airport_detail->save();

        if($airport_detail)
        {
            return back()->with('flash_success',$message);
        }
        else
        {
            return back()->with('flash_error',tr('admin_not_error'));
        }
        }
    }

    public function delete_airport_detail(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->airport_details !='' && $user->airport_details !=0 && in_array(DELET, explode(',', $user->airport_details))){
            $airport_detail = AirportDetail::find($request->id)->delete();

            if($airport_detail)
            {
                return back()->with('flash_success',tr('admin_not_st_del'));
            }
            else
            {
                return back()->with('flash_error',"Something went Wrong");
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    //Location Details

    public function location_details()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->destination_details !='' && $user->destination_details !=0 && in_array(VIEW_ALL, explode(',', $user->destination_details))){
            $location_detail = LocationDetail::all();
            return view('admin.airport.location_details')->with('location_details',$location_detail)->withPage('airport')->with('sub_page','location-details');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function edit_location_detail(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->destination_details !='' && $user->destination_details !=0 && in_array(EDIT, explode(',', $user->destination_details))){
            $location_detail = LocationDetail::find($request->id);
            $key = Helper::getKey();
            return view('admin.airport.add_location_detail')->with('name', 'Edit Location Details')->withKey($key)->with('location_detail',$location_detail)
                        ->withPage('airport')->with('sub_page','location-details');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_location_detail()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->destination_details !='' && $user->destination_details !=0 && in_array(ADD, explode(',', $user->destination_details))){
            $key = Helper::getKey();
            return view('admin.airport.add_location_detail')->withPage('airport')->withKey($key)->with('sub_page','location-details');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_location_detail_process(Request $request)
    {
        $validator = Validator::make(
          $request->all(),
          array(
              'name' => 'required|max:255',
              'zipcode' => 'required|max:255',
            )
        );
  
        if($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {
            if($request->id != '')
            {
                $location_detail = LocationDetail::find($request->id);
                
                $message = tr('admin_not_st_updated');
            }
            else
            {
                $location_detail = new LocationDetail;
                $message = tr('admin_not_st');

            }

            $location_detail->name = $request->name;
            $location_detail->latitude = $request->latitude;
            $location_detail->longitude = $request->longitude;
            $location_detail->zipcode = $request->zipcode;
            $location_detail->save();

        if($location_detail)
        {
            return back()->with('flash_success',$message);
        }
        else
        {
            return back()->with('flash_error',tr('admin_not_error'));
        }
        }
    }

    public function delete_location_detail(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->destination_details !='' && $user->destination_details !=0 && in_array(DELET, explode(',', $user->destination_details))){
            $location_detail = LocationDetail::find($request->id)->delete();

            if($location_detail)
            {
                return back()->with('flash_success',tr('admin_not_st_del'));
            }
            else
            {
                return back()->with('flash_error',"Something went Wrong");
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    //Airport Pricing Details

    public function airport_pricings()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->pricing_management !='' && $user->pricing_management !=0 && in_array(VIEW_ALL, explode(',', $user->pricing_management))){
            $airport_pricing = AirportPrice::all();
            // echo count($airport_pricing); exit;

            return view('admin.airport.airport_price')->with('airport_pricings',$airport_pricing)
                ->withPage('airport')->with('sub_page','airport-pricing');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function edit_airport_pricing(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->pricing_management !='' && $user->pricing_management !=0 && in_array(EDIT, explode(',', $user->pricing_management))){
            $airport_pricing = AirportPrice::find($request->id);
            $airport_details = AirportDetail::all();
            $location_details = LocationDetail::all();
            $service_types = ServiceType::all();
            return view('admin.airport.add_airport_price')->with('name', 'Edit Airport Pricing')->with('airport_price',$airport_pricing)
                        ->with('airport_details',$airport_details)->with('location_details',$location_details)
                        ->with('service_types',$service_types)
                        ->withPage('airport')->with('sub_page','airport-pricing');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_airport_pricing()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->pricing_management !='' && $user->pricing_management !=0 && in_array(ADD, explode(',', $user->pricing_management))){
            $airport_details = AirportDetail::all();
            $location_details = LocationDetail::all();
            $service_types = ServiceType::all();
            return view('admin.airport.add_airport_price')
                    ->with('airport_details',$airport_details)->with('location_details',$location_details)
                    ->with('service_types',$service_types)
                    ->withPage('airport')->with('sub_page','airport-pricing');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_airport_pricing_process(Request $request)
    {
        $validator = Validator::make(
          $request->all(),
          array(
              'airport_detail' => 'required|exists:airport_details,id',
              'location_detail' => 'required|exists:location_details,id',
              'service_type' => 'required|exists:service_types,id',
              'price' => 'required',
              'number_tolls' => 'numeric',
            )
        );
  
        if($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {
            if($request->id != '')
            {
                $airport_pricing = AirportPrice::find($request->id);
                
                $message = tr('admin_not_st_updated');
            }
            else
            {
                $airport_pricing = new AirportPrice;
                $message = tr('admin_not_st');

            }

            $airport_pricing->airport_details_id = $request->airport_detail;
            $airport_pricing->location_details_id = $request->location_detail;
            $airport_pricing->service_type_id = $request->service_type;
            $airport_pricing->number_tolls = $request->number_tolls;
            $airport_pricing->price = $request->price;
            $airport_pricing->save();

        if($airport_pricing)
        {
            return back()->with('flash_success',$message);
        }
        else
        {
            return back()->with('flash_error',tr('admin_not_error'));
        }
        }
    }

    public function delete_airport_pricing(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->pricing_management !='' && $user->pricing_management !=0 && in_array(DELET, explode(',', $user->pricing_management))){
            $airport_pricing = AirportPrice::find($request->id)->delete();

            if($airport_pricing)
            {
                return back()->with('flash_success',tr('admin_not_st_del'));
            }
            else
            {
                return back()->with('flash_error',"Something went Wrong");
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function provider_reviews()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->provider_ratings !='' && $user->provider_ratings !=0 && in_array(VIEW_ALL, explode(',', $user->provider_ratings))){
            $provider_reviews = DB::table('provider_ratings')
                ->leftJoin('providers', 'provider_ratings.provider_id', '=', 'providers.id')
                ->leftJoin('users', 'provider_ratings.user_id', '=', 'users.id')
                ->select('provider_ratings.id as review_id', 'provider_ratings.rating', 'provider_ratings.comment', 'users.first_name as user_first_name', 'users.last_name as user_last_name', 'providers.first_name as provider_first_name', 'providers.last_name as provider_last_name', 'users.id as user_id', 'providers.id as provider_id', 'provider_ratings.created_at')
                ->orderBy('provider_ratings.id', 'DESC')
                ->get();


            return view('admin.reviews')
                        ->with('reviews', $provider_reviews)
                        ->withPage('rating_review')->with('sub_page','provider-review');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function user_reviews()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->user_ratings !='' && $user->user_ratings !=0 && in_array(VIEW_ALL, explode(',', $user->user_ratings))){
            $user_reviews = DB::table('user_ratings')
                ->leftJoin('providers', 'user_ratings.provider_id', '=', 'providers.id')
                ->leftJoin('users', 'user_ratings.user_id', '=', 'users.id')
                ->select('user_ratings.id as review_id', 'user_ratings.rating', 'user_ratings.comment', 'users.first_name as user_first_name', 'users.last_name as user_last_name', 'providers.first_name as provider_first_name', 'providers.last_name as provider_last_name', 'users.id as user_id', 'providers.id as provider_id', 'user_ratings.created_at')
                ->orderBy('user_ratings.id', 'ASC')
                ->get();
            return view('admin.reviews')->with('name', 'User')->with('reviews', $user_reviews)
                      ->withPage('rating_review')->with('sub_page','user-review');
        }else{
             return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function delete_user_reviews(Request $request) {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->user_ratings !='' && $user->user_ratings !=0 && in_array(DELET, explode(',', $user->user_ratings))){
            $user = UserRating::find($request->id)->delete();
            return back()->with('flash_success', tr('admin_not_ur_del'));
        }else{
             return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function delete_provider_reviews(Request $request) {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->provider_ratings !='' && $user->provider_ratings !=0 && in_array(DELET, explode(',', $user->provider_ratings))){
            $provider = ProviderRating::find($request->id)->delete();
            return back()->with('flash_success', tr('admin_not_pr_del'));
        }else{
             return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }
 public function user_history(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->users !='' && $user->users !=0 && in_array(VIEW_HISTORY, explode(',', $user->users))){
            $requests = DB::table('requests')
                    ->Where('user_id',$request->id)
                    ->leftJoin('providers', 'requests.confirmed_provider', '=', 'providers.id')
                    ->leftJoin('users', 'requests.user_id', '=', 'users.id')
                    ->leftJoin('request_payments', 'requests.id', '=', 'request_payments.request_id')
                    ->select('users.first_name as user_first_name', 'users.last_name as user_last_name', 'providers.first_name as provider_first_name', 'providers.last_name as provider_last_name', 'users.id as user_id', 'providers.id as provider_id', 'requests.is_paid',  'requests.id as id', 'requests.created_at as date', 'requests.confirmed_provider', 'requests.status', 'requests.provider_status', 'requests.amount', 'requests.cancellation_fine','request_payments.payment_mode as payment_mode', 'requests.admin_id','requests.manager_id','request_payments.status as payment_status')
                    ->orderBy('requests.created_at', 'ASC')
                    ->get();
                    //dd($requests);
            return view('admin.request')->with('requests', $requests)->withPage('users')->with('sub_page','view-user')
                  ->with('name','view_user_history');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function user_history_old(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->users !='' && $user->users !=0 && in_array(VIEW_HISTORY, explode(',', $user->users))){
            $requests = DB::table('requests')
                    ->Where('user_id',$request->id)
                    ->leftJoin('providers', 'requests.confirmed_provider', '=', 'providers.id')
                    ->leftJoin('users', 'requests.user_id', '=', 'users.id')
                    ->leftJoin('request_payments', 'requests.id', '=', 'request_payments.request_id')
                    ->select('users.first_name as user_first_name', 'users.last_name as user_last_name', 'providers.first_name as provider_first_name', 'providers.last_name as provider_last_name', 'users.id as user_id', 'providers.id as provider_id', 'requests.is_paid',  'requests.id as id', 'requests.created_at as date', 'requests.confirmed_provider', 'requests.status', 'requests.provider_status', 'requests.amount', 'request_payments.payment_mode as payment_mode', 'request_payments.status as payment_status')
                    ->orderBy('requests.created_at', 'ASC')
                    ->get();
            return view('admin.request')->with('requests', $requests)->withPage('users')->with('sub_page','view-user')
                  ->with('name','view_user_history');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

 public function provider_history(Request $request)
    { 
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->providers !='' && $user->providers !=0 && in_array(VIEW_HISTORY, explode(',', $user->providers))){
            $requests = DB::table('requests')
                    ->Where('confirmed_provider',$request->id)
                    ->leftJoin('providers', 'requests.confirmed_provider', '=', 'providers.id')
                    ->leftJoin('users', 'requests.user_id', '=', 'users.id')
                    ->leftJoin('request_payments', 'requests.id', '=', 'request_payments.request_id')
                    ->select('users.first_name as user_first_name', 'users.last_name as user_last_name', 'providers.first_name as provider_first_name', 'providers.last_name as provider_last_name', 'users.id as user_id', 'providers.id as provider_id', 'requests.is_paid',  'requests.id as id', 'requests.admin_id', 'requests.created_at as date', 'requests.confirmed_provider', 'requests.status', 'requests.provider_status','requests.cancellation_fine', 'requests.amount','requests.manager_id', 'request_payments.payment_mode as payment_mode', 'request_payments.status as payment_status')
                    ->orderBy('requests.created_at', 'DESC')
                    ->get();
           //dd($requests);
            return view('admin.request')->with('requests', $requests)->withPage('providers')->with('sub_page','view-provider')
                  ->with('name','view_provider_history');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function provider_history_old(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->providers !='' && $user->providers !=0 && in_array(VIEW_HISTORY, explode(',', $user->providers))){
            $requests = DB::table('requests')
                    ->Where('confirmed_provider',$request->id)
                    ->leftJoin('providers', 'requests.confirmed_provider', '=', 'providers.id')
                    ->leftJoin('users', 'requests.user_id', '=', 'users.id')
                    ->leftJoin('request_payments', 'requests.id', '=', 'request_payments.request_id')
                    ->select('users.first_name as user_first_name', 'users.last_name as user_last_name', 'providers.first_name as provider_first_name', 'providers.last_name as provider_last_name', 'users.id as user_id', 'providers.id as provider_id', 'requests.is_paid',  'requests.id as id', 'requests.admin_id', 'requests.created_at as date', 'requests.confirmed_provider', 'requests.status', 'requests.provider_status', 'requests.amount','requests.manager_id', 'request_payments.payment_mode as payment_mode', 'request_payments.status as payment_status')
                    ->orderBy('requests.created_at', 'DESC')
                    ->get();
            return view('admin.request')->with('requests', $requests)->withPage('providers')->with('sub_page','view-provider')
                  ->with('name','view_provider_history');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

public function requests()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->ride_requests_management !='' && $user->ride_requests_management !=0 && in_array(VIEW_ALL, explode(',', $user->ride_requests_management))){
            $requests = DB::table('requests')
                    ->leftJoin('providers', 'requests.confirmed_provider', '=', 'providers.id')
                    ->leftJoin('users', 'requests.user_id', '=', 'users.id')
                    ->leftJoin('request_payments', 'requests.id', '=', 'request_payments.request_id')
                    ->select('users.first_name as user_first_name', 'users.last_name as user_last_name', 'providers.first_name as provider_first_name', 'providers.last_name as provider_last_name', 'users.id as user_id', 'providers.id as provider_id', 'requests.cancellation_fine', 'requests.is_paid', 'requests.manager_id',  'requests.id as id','requests.admin_id', 'requests.created_at as date', 'requests.confirmed_provider', 'requests.status', 'requests.provider_status', 
                        //'request_payments.total as amount', 
                        'requests.amount as amount','request_payments.payment_mode as payment_mode', 'request_payments.status as payment_status')
                    ->orderBy('requests.created_at', 'desc')
                    ->get();
            return view('admin.request')->with('requests', $requests)->withPage('requests')->with('sub_page','');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }
    public function requests_old()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->ride_requests_management !='' && $user->ride_requests_management !=0 && in_array(VIEW_ALL, explode(',', $user->ride_requests_management))){
            $requests = DB::table('requests')
                    ->leftJoin('providers', 'requests.confirmed_provider', '=', 'providers.id')
                    ->leftJoin('users', 'requests.user_id', '=', 'users.id')
                    ->leftJoin('request_payments', 'requests.id', '=', 'request_payments.request_id')
                    ->select('users.first_name as user_first_name', 'users.last_name as user_last_name', 'providers.first_name as provider_first_name', 'providers.last_name as provider_last_name', 'users.id as user_id', 'providers.id as provider_id', 'requests.cancellation_fine', 'requests.is_paid', 'requests.manager_id',  'requests.id as id','requests.admin_id', 'requests.created_at as date', 'requests.confirmed_provider', 'requests.status', 'requests.provider_status', 
                        //'request_payments.total as amount', 
                        'requests.amount as amount','request_payments.payment_mode as payment_mode', 'request_payments.status as payment_status')
                    ->orderBy('requests.created_at', 'desc')
                    ->get();
            return view('admin.request')->with('requests', $requests)->withPage('requests')->with('sub_page','');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }
    public function view_request(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->ride_requests_management !='' && $user->ride_requests_management !=0 && in_array(VIEW_REQUEST, explode(',', $user->ride_requests_management))){
            $requests = DB::table('requests')
                    ->where('requests.id',$request->id)
                    ->leftJoin('providers', 'requests.confirmed_provider', '=', 'providers.id')
                    ->leftJoin('users', 'requests.user_id', '=', 'users.id')
                    ->leftJoin('request_payments', 'requests.id', '=', 'request_payments.request_id')
                    ->select('users.first_name as user_first_name', 'users.last_name as user_last_name', 'providers.first_name as provider_first_name', 'providers.last_name as provider_last_name', 'users.id as user_id', 'providers.id as provider_id', 'requests.is_paid',  'requests.id as id', 'requests.created_at as date', 'requests.confirmed_provider', 'requests.status', 'requests.provider_status', 'requests.amount', 'request_payments.payment_mode as payment_mode', 'request_payments.status as payment_status', 'request_payments.total_time as total_time','request_payments.base_price as base_price', 'request_payments.time_price as time_price', 'request_payments.tax_price as tax', 'request_payments.total as total_amount', 'requests.s_latitude as latitude', 'requests.s_longitude as longitude','requests.start_time','requests.end_time','requests.s_address as request_address','requests.d_address as request_address1','requests.before_image' , 'requests.after_image')
                    ->first();
            return view('admin.request-view')->with('page' ,'requests')->with('sub-page' , "")->with('request', $requests);
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function cancel_request(Request $request) {
    $user = Admin::find(Auth::guard('admin')->user()->id);
    if($user->ride_requests_management !='' && $user->ride_requests_management !=0 && in_array(CANCEL_REQUEST, explode(',', $user->ride_requests_management))){
            $user_id = Auth::guard('admin')->user()->id;
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
                        $requests->request_cancelled = 1;
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

                            \Log::info("Cancelled request by user");
                            // Send mail notification to the provider
                            $email_data = array();

                            $subject = Helper::tr('request_cancel_user');

                            $email_data['provider_name'] = $email_data['username'] = "";

                            if($user = Admin::find($requests->user_id)) {
                                $email_data['username'] = $user->name;
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
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function mapview()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->driver_availability_stats !='' && $user->driver_availability_stats !=0 && in_array(VIEW_ALL, explode(',', $user->driver_availability_stats))){
            $Providers = Provider::all();
            $page = 'maps';
            $sub_page = 'provider-map';
            return view('admin.map', compact('Providers','page','sub_page'));
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function usermapview()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->booking_stats !='' && $user->booking_stats !=0 && in_array(VIEW_ALL, explode(',', $user->booking_stats))){
            $Users = User::where('latitude', '!=', '0')->where('longitude', '!=', '0')->get();
            $page = 'maps';
            $sub_page = 'user-map';
            return view('admin.user-map', compact('Users','page','sub_page'));
        }   
        else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }

    }

    public function help()
    {
        return view('admin.help');
    }

    public function provider_details(Request $request)
    {
        $provider = Provider::find($request->id);
        $avg_rev = ProviderRating::where('provider_id',$request->id)->avg('rating');


        if($provider) {
            $service = "";
            $service_type = ProviderService::where('provider_id' ,$provider->id)
                                ->leftJoin('service_types' ,'provider_services.service_type_id','=' , 'service_types.id')
                                ->first();
            if($service_type) {
                $service = $service_type->name;
            }
            return view('admin.provider-details')->with('provider' , $provider)->withService($service)->with('review',$avg_rev)
                      ->withPage('maps')->with('sub_page','provider-map');
        } else {
            return back()->with('error' , "Provider details not found");
        }
    }

    public function user_details(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->users !='' && $user->users !=0 && in_array(VIEW_DETAILS, explode(',', $user->users))){
            $user = User::find($request->id);
            $avg_rev = UserRating::where('user_id',$request->id)->avg('rating');

            if($user) {
              if($request->option !='')
                return view('admin.user-details')->with('user' , $user)->with('review',$avg_rev)
                          ->withPage('users')->with('sub_page','view-user')->with('name',$request->option);
              else
                return view('admin.user-details')->with('user' , $user)->with('review',$avg_rev)
                            ->withPage('maps')->with('sub_page','user-map');
            } else {
                return back()->with('error' , "User details not found");
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function promo_codes()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->promo_codes !='' && $user->promo_codes !=0 && in_array(VIEW_ALL, explode(',', $user->promo_codes))){
            $promo_codes = DB::table('promo_codes')->get();
            return view('admin.promo_codes')->with('promocodes',$promo_codes)->withPage('promo_codes')->with('sub_page','view-promocode');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_promo_code()
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->promo_codes !='' && $user->promo_codes !=0 && in_array(ADD, explode(',', $user->promo_codes))){
            return view('admin.add_promo_code')->withPage('promo_codes')->with('sub_page','add-promocode');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function edit_promo_code(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->promo_codes !='' && $user->promo_codes !=0 && in_array(EDIT, explode(',', $user->promo_codes))){
            $promo_code = PromoCode::find($request->id);
            // print_r($promo_code); exit;
            $start = explode(' ', $promo_code->start); 
           $start_1 = date("g:i a", strtotime($start[1])); 

             $end = explode(' ', $promo_code->end); 
           $end_1 = date("g:i a", strtotime($end[1])); 

            // echo date("h:i", strtotime($start[1])); exit;
            // print_r($start); exit;
            return view('admin.add_promo_code')->withPage('promo_codes')->with('start_1',$start_1)->with('end_1',$end_1)->with('sub_page','add-promocode')->with('promo_code',$promo_code);
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function add_promo_code_process(Request $request)
    {
      if($request->id != '')
      {
          $validator = Validator::make(
              $request->all(),
              array(
                  'start_date' => 'required',
                  'start_time' => 'required',
                  'end_date' => 'required',
                  'end_time' => 'required',
                  'short_description' => 'required|max:100',
                  'long_description' => 'required',
                  'coupon_code' => 'required',
                  'value' => 'required',
                  'max_promo' => 'required',
                  'max_usage' => 'required',
              )
          );
      }
      else {
        $validator = Validator::make(
              $request->all(),
              array(
                  'start_date' => 'required',
                  'start_time' => 'required',
                  'end_date' => 'required',
                  'end_time' => 'required',
                  'short_description' => 'required|max:100',
                  'long_description' => 'required',
                  'coupon_code' => 'required',
                  'value' => 'required',
                  'max_promo' => 'required',
                  'max_usage' => 'required',
              )
          );
      }
        if($validator->fails())
        {
            $error_messages = implode(',', $validator->messages()->all());
            return back()->with('flash_errors', $error_messages);
        }
        else
        {

            $time = $request->start_time;
            $chunks = explode(':', $time);
            if (strpos( $time, 'AM') === false && $chunks[0] !== '12') {
                $chunks[0] = $chunks[0] + 12;
            } else if (strpos( $time, 'PM') === false && $chunks[0] == '12') {
                $chunks[0] = '00';
            }

            $request->start_time = preg_replace('/\s[A-Z]+/s', '', implode(':', $chunks));

            $time = $request->end_time;
            $chunks = explode(':', $time);
            if (strpos( $time, 'AM') === false && $chunks[0] !== '12') {
                $chunks[0] = $chunks[0] + 12;
            } else if (strpos( $time, 'PM') === false && $chunks[0] == '12') {
                $chunks[0] = '00';
            }

            $request->end_time = preg_replace('/\s[A-Z]+/s', '', implode(':', $chunks));

            // echo $request->start_time;
            // echo date("H:i", strtotime($request->start_time)) exit;

            if($request->id != '')
            {
                $promo_codes = PromoCode::find($request->id);
                $message = tr('admin_not_st_updated');
            }
            else
            {
                $promo_codes = new PromoCode;
                $message = "Promo code has been added successfully";

            }
            $start_time = explode(' ', $request->start_time);
            $start = $request->start_date." ".$start_time[0]; 

            $end_time = explode(' ', $request->end_time);
            $end = $request->end_date." ".$end_time[0]; 

            $promo_codes->scope = DEFAULT_TRUE;
            $promo_codes->coupon_code = $request->coupon_code;
            $promo_codes->value = $request->value;
            $promo_codes->type = $request->type;
            $promo_codes->start = $start;
            $promo_codes->end = $end;
            $promo_codes->short_description = $request->short_description;
            $promo_codes->long_description = $request->long_description;
            $promo_codes->max_promo = $request->max_promo;
            $promo_codes->max_usage = $request->max_usage;
            $promo_codes->save();

        if($promo_codes)
        {
            return back()->with('flash_success',$message);
        }
        else
        {
            return back()->with('flash_error',tr('admin_not_error'));
        }
        }
    }

    public function delete_promo_code(Request $request)
    {
                $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->promo_codes !='' && $user->promo_codes !=0 && in_array(DELET, explode(',', $user->promo_codes))){
            if($provider = PromoCode::find($request->id))
            {

                $provider = PromoCode::find($request->id)->delete();
            }

            if($provider)
            {
                return back()->with('flash_success', 'Promo Code Has Been Deleted Successfully.');
            }
            else
            {
                return back()->with('flash_error',tr('admin_not_error'));
            }
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function push_notifications(Request $request)
    {
        $user = Admin::find(Auth::guard('admin')->user()->id);
        if($user->push_notifications !='' && $user->push_notifications !=0 && in_array(VIEW_ALL, explode(',', $user->push_notifications))){
            $result = User::where("device_token", "!=", "")->where("device_type", "!=", "")->get();
            return view('admin.push_notifications')->withResult($result)->withPage('push_notifications')->with('sub_page','view-push_notifications');
        }else{
            return redirect()->route('admin.dashboard')->with('flash_error', "Sorry, You don't have the permission");
        }
    }

    public function mass_push_notification_send(Request $request)
    {
        $type = $request->type;
        if($type == "users")
        {
            $user_type  = 1;
        }
        else if($type == "providers")
        {
            $user_type = 2;
        }
        $numbers      = $request->numbers;
        $push_title      = $request->push_title;
        $push_message      = $request->push_message;
        // $push_message    = strip_tags($push_message); 
            $validator = Validator::make(
                array(
                    'push_title' => $push_title,
                    'push_message' => $push_message,
                    'numbers' => $numbers,
                ), array(
                    'push_title' => 'required',
                    'numbers' => 'required',
                    'push_message' => 'required'
                )
            );

            if ($validator->fails()) {
                $error_messages = implode(',', $validator->messages()->all());
                return back()->with('flash_errors', $error_messages);
            }
            else
            {
                // echo count($numbers); exit;
                foreach($numbers as $id){
                    Helper::send_notifications($id, $user_type, $push_title, $push_message );
                }
                return back()->with('flash_success', 'Push notification sent successfully.');
            }
    }


}
