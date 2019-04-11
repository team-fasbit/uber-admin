<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsServiceType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_types', function(Blueprint $table){
            $table->string('color')->after('provider_name');
            $table->string('model')->after('color');
            $table->string('plate_no')->after('model');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_types', function(Blueprint $table){
            $table->dropColumn('color');
            $table->dropColumn('model');
            $table->dropColumn('plate_no');
        });
    }
}
