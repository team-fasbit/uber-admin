<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;
use App\Provider;
use App\Settings;
use App\ProviderRating;
use App\Requests;
use App\Helpers\Helper;
use Log;

class NormalPushNotification extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $id;
    protected $user_type;
    protected $title;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id,$user_type,$title,$message)
    {
        $this->id = $id;
        $this->user_type = $user_type;
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Request Push Notification Queue Started");

        // Check the user type whether "USER" or "PROVIDER"
        if($this->user_type == 0) {
            $user = User::find($this->id);
        } else {
            $user = Provider::find($this->id);
        }

        $push_data = array();

        $push_message = array('success' => true,'message' => $this->message,'data' => array((object) $push_data));

        $push_notification = 1; // Check the push notifictaion is enabled

        if ($push_notification == 1) {

            if ($user->device_type == 'ios') {

                Log::info("iOS push Started");

                require_once app_path().'/ios/apns.php';

                $msg = array("alert" => "" . $this->title,
                    "status" => "success",
                    "title" => $this->title,
                    // "message" => $push_message,
                    "badge" => 1,
                    "sound" => "default",
                    "status" => "",
                    "rid" => "",
                    );

                if (!isset($user->device_token) || empty($user->device_token)) {
                    $deviceTokens = array();
                } else {
                    $deviceTokens = $user->device_token;
                }
                
                $apns = new \Apns();
                $apns->send_notification($deviceTokens, $msg);

                Log::info("iOS push end");

            } else if ($user->device_type == 'android') {

                $push_message = array(
                        'success' => true,
                        'title' => $this->message,
                        'type' => '',
                        'data' => 'No Data'
                    );

                Log::info("Andriod push Started");

                require_once app_path().'/gcm/GCM_1.php';
                require_once app_path().'/gcm/const.php';

                if (!isset($user->device_token) || empty($user->device_token)) {
                    $registatoin_ids = "0";
                } else {
                    $registatoin_ids = trim($user->device_token);
                }
                if (!isset($push_message) || empty($push_message)) {
                    $msg = "Message not set";
                } else {
                    $msg = $push_message;
                }
                if (!isset($this->title) || empty($this->title)) {
                    $title1 = "Message not set";
                } else {
                    $title1 = trim($this->title);
                }

                $message = array(TEAM => $title1, MESSAGE => $msg);

                $gcm = new \GCM();
                $registatoin_ids = array($registatoin_ids);
                $gcm->send_notification($registatoin_ids, $message);

                Log::info("Andriod push end");

            }

        } else {
            Log::info('Push notifictaion is not enabled. Please contact admin');
        }

    }
}
