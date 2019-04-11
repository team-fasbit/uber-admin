<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPromoCodeIntoReqPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_payments', function (Blueprint $table) {
            $table->string('promo_code')->after('booking_fee');
            $table->float('promo_value')->after('promo_code');
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
            $table->dropColumn('promo_code');
            $table->dropColumn('promo_value');
        });
    }
}
