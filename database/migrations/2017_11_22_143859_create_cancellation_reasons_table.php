<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCancellationReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('cancellation_reasons', function(Blueprint $table)
      {
          $table->increments('id');
          $table->string('cancel_reason');
          $table->float('cancel_fee');
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
        Schema::drop('cancellation_reasons');
    }
}
