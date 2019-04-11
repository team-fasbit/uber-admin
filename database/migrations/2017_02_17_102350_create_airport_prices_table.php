<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirportPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airport_prices', function (Blueprint $table) {
          $table->increments('id');
          $table->string('package_name');
          $table->integer('airport_details_id');
          $table->integer('location_details_id');
          $table->integer('service_type_id');
          $table->float('price');
          $table->integer('number_tolls');
          $table->integer('status');
          $table->timestamps();

//          $table->foreign('airport_details_id')->references('id')->on('airport_details')->onDelete('cascade');

  //        $table->foreign('location_details_id')->references('id')->on('location_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('airport_prices');
    }
}
