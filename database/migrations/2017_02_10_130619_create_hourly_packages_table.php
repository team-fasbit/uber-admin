<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHourlyPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hourly_packages', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('number_hours');
          $table->float('price');
          $table->float('distance');
          $table->integer('car_type_id');
          $table->integer('status');
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hourly_packages');
    }
}
