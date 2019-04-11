<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->string('payment_method_nonce');
            $table->string('paypal_email');
            $table->string('card_type')->default('na');
            $table->boolean('is_deleted')->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn('payment_method_nonce');
            $table->dropColumn('paypal_email');
            $table->dropColumn('card_type')->default('na');
            $table->dropColumn('is_deleted')->default(0);
        });
    }
}
