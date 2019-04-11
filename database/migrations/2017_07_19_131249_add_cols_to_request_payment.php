<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToRequestPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_payments', function (Blueprint $table) {
            $table->float('min_fare')->after('base_price');
            $table->float('total_time', 11)->change();
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
            $table->dropColumn('min_fare');
        });
    }
}
