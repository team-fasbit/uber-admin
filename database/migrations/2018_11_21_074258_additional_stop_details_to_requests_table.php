<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdditionalStopDetailsToRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {   
            $table->integer('is_adstop')->default(0);
            $table->string('adstop_address');
            $table->double('adstop_latitude',15,8);
            $table->double('adstop_longitude',15,8);
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
            $table->dropColumn('is_adstop');
            $table->dropColumn('adstop_address');
            $table->dropColumn('adstop_latitude',15,8);
            $table->dropColumn('adstop_longitude',15,8);
        });
    }
}
