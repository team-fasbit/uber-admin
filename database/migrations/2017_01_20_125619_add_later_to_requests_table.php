<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLaterToRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('requests', function (Blueprint $table) {
          $table->integer('later')->after('request_start_time');
          $table->dateTime('requested_time')->after('later');
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
          $table->dropColumn('date');
          $table->dropColumn('requested_time');
      });
    }
}
