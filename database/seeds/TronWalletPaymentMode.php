<?php

use Illuminate\Database\Seeder;

class TronWalletPaymentMode extends Seeder
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
 		        'key' => 'tron_wallet',
 		        'value' => '1'
            ]

 		]);
    }
}
