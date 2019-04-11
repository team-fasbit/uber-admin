<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProviderServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('provider_services', function (Blueprint $table) {
         $table->increments('id');
         $table->integer('provider_id');
         $table->integer('service_type_id');
         $table->integer('status');
         $table->integer('is_available');
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
        Schema::drop('provider_services');
    }
}
