<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Validator;

use App\ServiceType;

use App\Helpers\Helper;

use App\Requests;

use App\WalkLocation;
use App\Settings;

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


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function service_list() {

        if($serviceList = ServiceType::all()) {
            $response_array = Helper::null_safe(array('success' => true,'services' => $serviceList));
        } else {
            $response_array = array('success' => false,'error' => Helper::get_error_message(115),'error_code' => 115);
        }
        $response = response()->json($response_array, 200);
        return $response;
    }

    public function location_update_trip(Request $request)
    {
      $sender = $request->sender;
      $receiver = $request->receiver;
      $latitude = $request->latitude;
      $longitude = $request->longitude;
      $validator = Validator::make(
          $request->all(),
          array(
              'sender' => 'required|exists:users,id',
              'receiver' => 'required|exists:providers,id',
              'request_id' => 'required|numeric',
              'latitude' => 'required|numeric',
              'longitude' => 'required|numeric',
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
        $requests = Requests::find($request->request_id);
        if ($requests->confirmed_provider == $request->provider_id) {

            if ($requests->status == REQUEST_INPROGRESS) {

              $walk_location_last = WalkLocation::where('request_id',$request->request_id)->orderBy('created_at','desc')->first();

              if($walk_location_last)
              {
                $distance_old = $walk_location_last->distance;
                $distance_new = Helper::distanceGeoPoints($walk_location_last->latitude,$walk_location_last->longitude,$latitude,$longitude);
                $distance =  $distance_old + $distance_new;
                $settings = Settings::where('key','default_distance_unit')->first();
                $unit = $settings->value;
                // Problem in convertion between miles and kms, need to be fixed.
                $distancecon = Helper::convert($distance, $unit);
              }
              else{
                $distance = 0;
              }

              $walk_location = new WalkLocation;
              $walk_location->request_id = $request->request_id;
              $walk_location->latitude = $request->latitude;
              $walk_location->longitude = $request->longitude;
              $walk_location->status = $request->status;
              $walk_location->distance = $distance;
              $walk_location->save();

              $loc1=WalkLocation::where('request_id', $requests->id)->first();
              $loc2= WalkLocation::where('request_id', $requests->id)->orderBy('id', 'desc')->first();
              if($loc1){
                  $time1=strtotime($loc2->created_at);
                  $time2=strtotime($loc1->created_at);
                  $difference=intval(($time1-$time2)/60);
              }else{
                  $difference=0;
              }

              $response_array = array('success' => true, 'distance' => $distancecon, 'unit' => $unit, 'time'=> $difference);

            }
          else
          {
              $walk_location_last = WalkLocation::where('request_id',$request->request_id)->orderBy('created_at','desc')->first();

              if($walk_location_last)
              {
                  $distance_old = $walk_location_last->distance;
                  $distance_new = Helper::distanceGeoPoints($walk_location_last->latitude,$walk_location_last->longitude,$latitude,$longitude);
                  $distance =  $distance_old + $distance_new;
                  $settings = Settings::where('key','default_distance_unit')->first();
                  $unit = $settings->value;

                  $distancecon = Helper::convert($distance, $unit);
              }
              else{
                  $distance = 0;
              }

              $walk_location = new WalkLocation;
              $walk_location->request_id = $request->request_id;
              $walk_location->latitude = $request->latitude;
              $walk_location->longitude = $request->longitude;
              $walk_location->status = $request->status;
              $walk_location->distance = $distance;
              $walk_location->save();

              $loc1=WalkLocation::where('request_id', $requests->id)->first();
              $loc2= WalkLocation::where('request_id', $requests->id)->orderBy('id', 'desc')->first();
              if($loc1){
                  $time1=strtotime($loc2->created_at);
                  $time2=strtotime($loc1->created_at);
                  $difference=intval(($time1-$time2)/60);
              }else{
                  $difference=0;
              }
              $response_array = array('success' => true);
            }
          } else {
              $response_array = array('success' => false,'error' => Helper::get_error_message(115),'error_code' => 115);
          }
      }
      $response = response()->json($response_array, 200);
      return $response;
  }

}
