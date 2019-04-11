<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Helper;

use App\Settings;

use App\Requests;

use App\RequestsMeta;

use App\ServiceType;

use App\User;

use App\Provider;

use App\ProviderService;

use App\ProviderAvailability;

use App\ChatMessage;

use App\Jobs\NormalPushNotification;

use App\Jobs\sendPushNotification;

use DB;

use Log;


if (!defined('USER')) define('USER',1);
if (!defined('PROVIDER')) define('PROVIDER',1);

if (!defined('NONE')) define('NONE', 0);

if (!defined('DEFAULT_FALSE')) define('DEFAULT_FALSE', 0);
if (!defined('DEFAULT_TRUE')) define('DEFAULT_TRUE', 1);

// Payment Constants
if (!defined('COD')) define('COD',   'cod');
if (!defined('PAYPAL')) define('PAYPAL', 'paypal');
if (!defined('CARD')) define('CARD',  'card');

if (!defined('REQUEST_NEW')) define('REQUEST_NEW',        0);
if (!defined('REQUEST_WAITING')) define('REQUEST_WAITING',      1);
if (!defined('REQUEST_INPROGRESS')) define('REQUEST_INPROGRESS',    2);
if (!defined('REQUEST_COMPLETE_PENDING')) define('REQUEST_COMPLETE_PENDING',  3);
if (!defined('REQUEST_RATING')) define('REQUEST_RATING',      4);
if (!defined('REQUEST_COMPLETED')) define('REQUEST_COMPLETED',      5);
if (!defined('REQUEST_CANCELLED')) define('REQUEST_CANCELLED',      6);
if (!defined('REQUEST_NO_PROVIDER_AVAILABLE')) define('REQUEST_NO_PROVIDER_AVAILABLE',7);
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

class ApplicationController extends Controller
{

	public function release_locked_providers_cron()
    {

        Log::info("CRON: release_locked_providers");

        //Get all the locked providers.
        $locked_provider_ids = Provider::whereWaiting_to_respond(WAITING_TO_RESPOND)
                            ->leftjoin('requests_meta', 'providers.id', '=', 'requests_meta.provider_id')
                            ->whereNull('requests_meta.provider_id')
                            ->lists('providers.id');

        //Release the locked providers.
        Provider::whereIn('id', $locked_provider_ids)->update(array('waiting_to_respond' => WAITING_TO_RESPOND_NORMAL));
    }

    public function assign_next_provider_cron()
    {

        Log::info("CRON STARTED");

        $settings = Settings::where('key', 'provider_select_timeout')->first();
        $provider_timeout = $settings->value;
        $time = date("Y-m-d H:i:s");
        //Log::info('assign_next_provider_cron ran at: '.$time);

        //Get all the new waiting requests which are not confirmed and not cancelled.
        $query = "SELECT id, user_id,request_type,provider_id, TIMESTAMPDIFF(SECOND,request_start_time, '$time') AS time_since_request_assigned
                  FROM requests
                  WHERE status = ".REQUEST_WAITING;
        $requests = DB::select(DB::raw($query));

        foreach ($requests as $request) {

            if ($request->time_since_request_assigned >= $provider_timeout) {

                $current_offered_provider = RequestsMeta::where('request_id',$request->id)
                                ->where('status', REQUEST_META_OFFERED)
                                ->first();

                $provider_id = array();

                if($current_offered_provider) {
                    $provider_id = $current_offered_provider->provider_id;
                }

                // To change the current provider availability and next provider status ,push notification changes
                Helper::assign_next_provider($request->id,$provider_id);

            } else {
                Log::info("Provider Waiting State");
            }
        }
    }

    public function scheduled_requests_cron()
    {
        Log::info('Scheduled Request Cron inside');

        $scheduled_request_start_before = 1800;
        $time = date("Y-m-d H:i:s");

        //Get all the new scheduled requests which are not cancelled.
        $query = "SELECT *, TIMESTAMPDIFF(SECOND, '$time',requested_time) AS time_since_request_scheduled
                  FROM requests
                  WHERE status = ".REQUEST_NEW." AND later=".DEFAULT_TRUE;

        $requests = DB::select(DB::raw($query));

        Log::info($requests);

        if($requests) {
            foreach ($requests as $request) {
                if ($request->time_since_request_scheduled <= $scheduled_request_start_before) {

                    $user = User::find($request->user_id);
                   
                    Log::info('Previous requests check is done');
                    $service_type = $request->request_type; // Get the service type

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
                    Log::info('Testing: Schedule Cron is running.');
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
                            Log::info($query);
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
                            Log::info("No Provider Found hey");
                            // Send push notification to User
                            // Log:info('User details:'.print_r($user,true));
                            // clear request details
                            $request_cancel = Requests::where('id', '=', $request->id)->update( array('status' => REQUEST_NO_PROVIDER_AVAILABLE));

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


                    if($request) {
                        // update request table
                        Log::info('request details'.print_r($request,true));

                        $request_start_time = time();
                        $request_update = Requests::find($request->id);

                        Log::info('request update'.print_r($request_update,true));
                        
                        $request_update->status = REQUEST_WAITING;
                        $request_update->request_start_time = date("Y-m-d H:i:s", $request_start_time);
                        //No need fo current provider state
                        // $request->current_provider = $first_provider_id;
                        $request_update->save();
                        if($request_update)
                            Log::info("REquest saved");

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
                                    $message = "You got a new request from ".$user->first_name." ".$user->last_name;

                                    $this->dispatch(new sendPushNotification($first_provider_id,2,$request->id,$title,$message,''));

                                    // Push End
                                }

                                $request_meta->request_id = $request->id;
                                $request_meta->provider_id = $final_provider;
                                $request_meta->save();
                            }
                        }
   
                        $response_d_address = $request->d_address ? $request->d_address : "";
                        $response_d_latitude = $request->d_latitude ? $request->d_latitude : "";
                        $response_d_longitude = $request->d_longitude ? $request->d_longitude : "";

                        $response_array = array(
                            'success' => true,
                            'request_id' => $request->id,
                            'current_provider' => $first_provider_id,
                            'address' => $request->s_address,
                            'latitude' => $request->s_latitude,
                            'longitude' => $request->s_longitude,
                            'd_address' => $response_d_address,
                            'd_latitude' => $response_d_latitude,
                            'd_longitude' => $response_d_longitude,
                        );

                        $response_array = Helper::null_safe($response_array); Log::info('Create request end');
                    } else {
                        $response_array = array('success' => false , 'error' => Helper::get_error_message(126) , 'error_code' => 126 );
                    }
                } else {
                    Log::info("No scheduled requests found at this time");
                }
            }
        } else {
            Log::info("No Scheduled requests found");
        }
}

    public function check_app_version()
    {
        Log::info("CHECK APP VERSION API");
        $settings = Settings::where('key', 'force_upgrade')->first();
        $force_upgrade = $settings->value;

        if($force_upgrade != 1){
            $response_array = array('success' => false);
        }else{

            $settings = Settings::where('key', 'android_user_version')->first();
            $android_user_version = $settings->value;

            $settings = Settings::where('key', 'android_driver_version')->first();
            $android_driver_version = $settings->value;

            $settings = Settings::where('key', 'ios_user_version')->first();
            $ios_user_version = $settings->value;

            $settings = Settings::where('key', 'ios_driver_version')->first();
            $ios_driver_version = $settings->value;

            $response_array = array('success' => true, 'android_user_version' => $android_user_version,'android_driver_version' => $android_driver_version, 'ios_user_version'=> $ios_user_version,'ios_driver_version'=> $ios_driver_version);

        }
        $response = response()->json($response_array , 200);
        return $response;

    }

    public function test_ios_user(){
        $token = "be97ad787134d89c88593e31e2e16ccfe83c89e2957b94771a5e63163c0bdbd2";
        $title = "Hello Test";
        $message = "Test message";
        $type=1;
        $res = Helper::send_ios_push($token, $title, $message, $type);
        dd($res);

    }


}
