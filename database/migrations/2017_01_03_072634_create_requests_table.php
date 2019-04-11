<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('requests', function (Blueprint $table)
      {
          $table->increments('id');
          $table->integer('provider_id');
          $table->integer('user_id');
          $table->integer('current_provider');
          $table->integer('confirmed_provider');
          $table->dateTime('request_start_time');
          $table->double('s_latitude',15,8);
          $table->double('s_longitude',15,8);
          $table->double('d_latitude',15,8);
          $table->double('d_longitude',15,8);
          $table->tinyInteger('is_paid');
          $table->string('s_address');
          $table->string('d_address');
          $table->dateTime('start_time');
          $table->dateTime('end_time');
          $table->integer('amount');
          $table->integer('status');
          $table->integer('provider_status');
          $table->integer('request_type');
          $table->integer('request_meta_id');
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
        Schema::drop('requests');
    }
}
