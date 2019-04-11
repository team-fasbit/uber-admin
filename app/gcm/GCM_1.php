<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
use App\Helpers\Helper;
$gcmkey = Helper::settings('gcm_key');
// define("GOOGLE_API_KEY", "AIzaSyCS6I1CHQFYZpn127TI2jKNODqaokCtrj8"); old
define("GOOGLE_API_KEY", $gcmkey); 

//change the browser key from admin panel

class GCM {

    //put your code here
    // constructor
    function __construct() {

    }

    /**
     * Sending Push Notification
     */
    public function send_notification($registatoin_ids, $message) {
        // include config
        include_once 'const.php';
        /* include_once 'config.php'; */
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
        );

        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );

        Log::info('***** PUSH MESSAGE ******'.print_r(json_encode($fields),true));

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            //die('Curl failed: ' . curl_error($ch));
            Log::error('Curl failed: ' . curl_error($ch));
        }
        else{
            //echo $result;
            Log::error($result);
        }

        // Close connection
        /*curl_close($ch);
         echo $result/*."\n\n".json_encode($fields); */
    }

}
?>
