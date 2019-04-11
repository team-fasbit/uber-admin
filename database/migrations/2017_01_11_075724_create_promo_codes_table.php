<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('promo_codes', function (Blueprint $table) {
          $table->increments('id');
          $table->string('coupon_code');
          $table->integer('value');
          $table->integer('type');
          $table->integer('uses');
          $table->integer('status');
          $table->datetime('expiry');
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
        Schema::drop('promo_codes');
    }
}
