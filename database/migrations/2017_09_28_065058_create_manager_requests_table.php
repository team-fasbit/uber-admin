<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->integer('caller_id');
            $table->string('service_type_id');
            $table->float('estimated_fare');
            $table->double('s_latitude',15,8);
            $table->double('s_longitude',15,8);
            $table->double('d_latitude',15,8);
            $table->double('d_longitude',15,8);
            $table->string('s_address');
            $table->string('d_address');
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
        Schema::drop('manager_requests');
    }
}
