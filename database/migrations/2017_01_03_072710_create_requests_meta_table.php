<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('requests_meta', function (Blueprint $table)
      {
          $table->increments('id');
          $table->integer('request_id');
          $table->integer('service_id');
          $table->integer('status');
          $table->integer('is_cancelled');
          $table->integer('provider_id');
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
        Schema::drop('requests_meta');
    }
}
