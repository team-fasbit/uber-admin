<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferralFieldsToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code');
            $table->integer('is_ref_used')->default(0);
            $table->float('referrer_bonus')->default(0);
            $table->float('referee_bonus')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('referral_code');
            $table->dropColumn('is_ref_used');
            $table->dropColumn('referrer_bonus');
            $table->dropColumn('referee_bonus');

        });
    }
}
