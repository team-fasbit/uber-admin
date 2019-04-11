<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToServiceTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_types', function (Blueprint $table) {
            $table->float('base_fare')->after('number_seat');
            $table->float('tax_fee')->after('min_fare');
            $table->float('booking_fee')->after('tax_fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_types', function (Blueprint $table) {
            $table->dropColumn('base_fare');
            $table->dropColumn('tax_fee');
            $table->dropColumn('booking_fee');
        });
    }
}
