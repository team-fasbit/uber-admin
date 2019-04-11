<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMinFareIntoServiceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_types',function (Blueprint $table) {
            $table->integer('number_seat');
            $table->float('min_fare');
            $table->float('price_per_min');
            $table->float('price_per_unit_distance');
            $table->enum('distance_unit', array('kms','miles'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('service_types',function (Blueprint $table) {
          $table->dropColumn('number_seat');
          $table->dropColumn('min_fare');
          $table->dropColumn('price_per_min');
          $table->dropColumn('price_per_unit_distance');
          $table->dropColumn('distance_unit');
      });
    }
}
