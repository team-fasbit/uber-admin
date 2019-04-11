<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPromoCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->integer('scope')->after('id');
            $table->renameColumn('expiry', 'start');
            $table->dateTime('end')->after('expiry');
            $table->mediumText('short_description')->after('end');
            $table->text('long_description')->after('short_description');
            $table->integer('max_promo')->after('long_description');
            $table->integer('max_usage')->after('max_promo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->dropColumn('scope');
            $table->dropColumn('end');
            $table->dropColumn('short_description');
            $table->dropColumn('long_description');
            $table->dropColumn('max_promo');
            $table->dropColumn('max_usage');
        });
    }
}
