<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertSingleValSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('settings')
        ->insert(
            array(
                array('id'=>129,'key' => 'wallet_url','value' => 'http://165.227.126.172')
                ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('settings')
        ->delete(
            array('id'=>129)
            );
    }
}
