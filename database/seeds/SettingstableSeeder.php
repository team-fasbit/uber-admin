<?php

use Illuminate\Database\Seeder;

class SettingstableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
     	DB::table('settings')->delete();
     	DB::table('settings')->insert([
     		[
 		        'key' => 'site_name',
 		        'value' => 'Smart Car'
 		    ],
 		    [
 		        'key' => 'site_logo',
 		        'value' => ''
 		    ],
 		    [
 		        'key' => 'site_icon',
 		        'value' => ''
 		    ],
 		    [
 		        'key' => 'provider_select_timeout',
 		        'value' => 60
 		    ],
 		    [
 		        'key' => 'search_radius',
 		        'value' => 100
 		    ],
 		    [
 		        'key' => 'base_price',
 		        'value' => 50
 		    ],
 		    [
 		        'key' => 'price_per_minute',
 		        'value' => 10
 		    ],
 		    [
 		        'key' => 'tax_price',
 		        'value' => 50
 		    ],
 		    [
 		        'key' => 'price_per_unit_distance',
 		        'value' => 10
 		    ],
 		    [
 		        'key' => 'stripe_secret_key',
 		        'value' => ''
 		    ],
 		     [
 		        'key' => 'stripe_publishable_key',
 		        'value' => ''
 		    ],
 		    [
 		        'key' => 'cod',
 		        'value' => 1
 		    ],
 		    [
 		        'key' => 'paypal',
 		        'value' => 1
 		    ],
 		    [
 		        'key' => 'card',
 		        'value' => 1
 		    ],
            [
                'key' => 'walletbay',
                'value' => 1
            ],
 		    [
 		        'key' => 'manual_request',
 		        'value' => 1
 		    ],
 		    [
 		        'key' => 'paypal_email',
 		        'value' => ''
 		    ],
 		    [
 		        'key' => 'default_lang',
 		        'value' => 'en'
 		    ],
 		    [
 		        'key' => 'currency',
 		        'value' => '$'
 		    ],
 		    [
 		        'key' => 'mail_logo',
 		        'value' => ''
 		    ],
 		    [
 		        'key' => 'default_distance_unit',
 		        'value' => 'miles' //miles or kms
 		    ],
 		    [
 		        'key' => 'price_per_service',
 		        'value' => 1 // 1 for price per service
 		    ],
            [
                'key' => 'wallet_bay_key',
                'value' => ''
            ],
            [
                'key' => 'wallet_url',
                'value' => 'http://walletbay.net/apps'
            ],
            [
                'key' => 'provider_commission',
                'value' => '60'
            ],
            [
                'key' => 'cancellation_fine',
                'value' => ''
            ],
            [
                'key' => 'gcm_key',
                'value' => ''
            ],
            [
                'key' => 'force_upgrade',
                'value' => 0
            ],
            [
                'key' => 'android_user_version',
                'value' => 1
            ],
            [
                'key' => 'android_driver_version',
                'value' => 1
            ],
            [
                'key' => 'ios_user_version',
                'value' => 1
            ],
            [
                'key' => 'ios_driver_version',
                'value' => 1
            ],
            [
                'key' => 'accept_debt_cash',
                'value' => 0
            ],
            [
                'key' => 'surge_status',
                'value' => 0
            ],
            [
                'key' => 'surge_a',
                'value' => 1
            ],
            [
                'key' => 'surge_b',
                'value' => 1
            ],
            [
                'key' => 'surge_c',
                'value' => 1
            ],
            [
                'key' => 'surge_d',
                'value' => 1
            ],
            [
                'key' => 'surge_e',
                'value' => 1
            ],
            [
                'key' => 'surge_f',
                'value' => 1
            ],
            [
                'key' => 'surge_g',
                'value' => 1
            ],
            [
                'key' => 'referrer_bonus',
                'value' => 0
            ],
            [
                'key' => 'referee_bonus',
                'value' => 0
            ],

 		]);
     }
}
