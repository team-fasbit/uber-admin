<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsProviderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->string('plate_no')->after('password');
            $table->string('model')->after('plate_no');
            $table->string('color')->after('model');
            $table->string('car_image')->after('color');
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
            $table->dropColumn('plate_no');
            $table->dropColumn('model');
            $table->dropColumn('color');
            $table->dropColumn('car_image');
        });
    }
}
