<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('request_payments', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('request_id');
          $table->string('payment_id');
          $table->string('payment_mode');
          $table->integer('total_time');
          $table->float('base_price');
          $table->float('time_price');
          $table->float('tax_price');
          $table->float('total');
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
        Schema::drop('request_payments');
    }
}
