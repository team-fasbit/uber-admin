<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('chat_messages', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('request_id');
          $table->integer('user_id');
          $table->integer('provider_id');
          $table->text('message');
          $table->enum('type',array('up','pu'));
          $table->boolean('delivered');
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
        Schema::drop('chat_messages');
    }
}
