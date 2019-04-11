<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentAndCardColumnsInManagerRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manager_requests', function (Blueprint $table) {
            $table->string('payment_mode')->after('email');
            $table->integer('default_card')->after('payment_mode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manager_requests', function (Blueprint $table) {
            $table->dropColumn('payment_mode');
            $table->dropColumn('default_card');
        });
    }
}
