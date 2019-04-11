<?php

use Illuminate\Database\Seeder;

class adminTronSettings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
     		[
 		        'key' => 'tron_address_base58',
 		        'value' => 'TLZdkGdKet4rMz4MShwJ8MzJSDuGvjFhYA'
            ],
            [
 		        'key' => 'tron_address_hex',
 		        'value' => 'TLZdkGdKet4rMz4MShwJ8MzJSDuGvjFhYA'
            ],
            [
 		        'key' => 'tron_private_key',
 		        'value' => ''
            ],
            [
 		        'key' => 'tron_api_url',
 		        'value' => 'http://46.101.106.16:3000'
            ],

 		]);
    }
}
