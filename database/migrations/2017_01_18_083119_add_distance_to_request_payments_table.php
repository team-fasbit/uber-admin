<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDistanceToRequestPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('request_payments',function (Blueprint $table) {
          $table->float('distance_travel');
          $table->float('distance_price');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('request_payments',function (Blueprint $table) {
          $table->dropColumn('distance_travel');
          $table->dropColumn('distance_price');
      });
    }
}
