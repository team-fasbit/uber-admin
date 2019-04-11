<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequestCancelledToRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->integer('cancel_dri_shown')->default(0);
            $table->integer('cancel_usr_shown')->default(0);
            $table->integer('request_cancelled')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('cancel_dri_shown');
            $table->dropColumn('cancel_usr_shown');
            $table->dropColumn('request_cancelled');
        });
    }
}
