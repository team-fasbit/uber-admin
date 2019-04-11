<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProvEarningReqPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_payments', function(Blueprint $table){
            $table->string('provider_earnings')->after('booking_fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_payments', function(Blueprint $table){
            $table->dropColumn('provider_earnings');
        });
    }
}
