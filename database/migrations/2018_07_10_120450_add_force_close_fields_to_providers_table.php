<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForceCloseFieldsToProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->float('app_version')->default(0);
            $table->integer('is_closed')->default(0);
            $table->dateTime('closed_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn('app_version')->default(0);
            $table->dropColumn('is_closed');
            $table->dropColumn('closed_time');
        });
    }
}
