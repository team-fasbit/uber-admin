<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('providers', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
          $table->string('first_name');
          $table->string('last_name');
          $table->string('email')->unique();
          $table->string('password');
          $table->string('picture');
          $table->string('token');
          $table->string('token_expiry');
          $table->string('device_token');
          $table->enum('device_type',array('android','ios'));
          $table->enum('login_by',array('manual','facebook','google'));
          $table->string('social_unique_id');
          $table->string('fb_lg');
          $table->string('gl_lg');
          $table->string('description');
          $table->integer('is_activated');
          $table->integer('is_approved');
          $table->integer('is_available');
          $table->integer('waiting_to_respond');
          $table->enum('gender',array('male','female','others'));
          $table->string('mobile');
          $table->double('latitude', 15, 8);
          $table->double('longitude',15,8);
          $table->string('paypal_email');
          $table->string('address');
          $table->string('email_activation_code');
          $table->string('is_email_activated');
          $table->string('token_refresh');
          $table->string('timezone');
          $table->string('currency_code');
          $table->string('country');
          $table->rememberToken();
          $table->softDeletes();
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
        Schema::drop('providers');
    }
}
