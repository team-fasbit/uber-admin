<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentModeAndDefaultCardInCallCenterManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('call_center_managers', function (Blueprint $table) {
             $table->string('payment_mode')->after('is_approved');
          $table->integer('default_card')->after('payment_mode');
          $table->string('timezone')->after('default_card');
          $table->string('currency_code')->after('timezone');
          $table->string('country')->after('currency_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('call_center_managers', function (Blueprint $table) {
            $table->dropColumn('payment_mode');
            $table->dropColumn('default_card');
            $table->dropColumn('timezone');
            $table->dropColumn('currency_code');
            $table->dropColumn('country');
        });
    }
}
