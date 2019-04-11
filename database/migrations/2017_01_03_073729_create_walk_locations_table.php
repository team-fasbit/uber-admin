<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalkLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('walk_locations', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('request_id');
          $table->double('latitude', 15, 8);
          $table->double('longitude',15,8);
          $table->double('distance',15,8);
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
        Schema::drop('walk_locations');
    }
}
