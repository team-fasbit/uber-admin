<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminTronWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_tron_wallet', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->boolean('is_default')->default(false);

            $table->string('private_key', 500)->default('');
            $table->string('public_key', 500)->default('');
            $table->string('address_base58', 500)->default('');
            $table->string('address_hex', 500)->default('');
          
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admin_tron_wallet');
    }
}
