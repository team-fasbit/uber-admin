<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOneColToRequestPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_payments', function (Blueprint $table) {
            $table->float('booking_fee')->after('tax_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_payments', function (Blueprint $table) {
            $table->dropColumn('booking_fee');
        });
    }
}
