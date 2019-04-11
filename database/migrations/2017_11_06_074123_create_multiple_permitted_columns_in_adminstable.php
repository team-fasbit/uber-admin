<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMultiplePermittedColumnsInAdminstable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('role')->nullable()->after('password');
            $table->string('dashboard')->after('role');
            $table->string('booking_stats')->after('dashboard');
            $table->string('driver_availability_stats')->after('booking_stats');
            $table->string('corporates')->after('driver_availability_stats');
            $table->string('users')->after('corporates');
            $table->string('providers')->after('users');
            $table->string('sub_admins')->after('providers');
            $table->string('ride_requests_management')->after('sub_admins');
            $table->string('vehicle_types')->after('ride_requests_management');
            $table->string('promo_codes')->after('vehicle_types');
            $table->string('rental_management')->after('promo_codes');
            $table->string('airport_details')->after('rental_management');
            $table->string('destination_details')->after('airport_details');
            $table->string('pricing_management')->after('destination_details');
            $table->string('provider_ratings')->after('pricing_management');
            $table->string('user_ratings')->after('provider_ratings');
            $table->string('documents_management')->after('user_ratings');
            $table->string('currency_management')->after('documents_management');
            $table->string('transactions')->after('currency_management');
            $table->string('push_notifications')->after('transactions');
            $table->string('settings')->after('push_notifications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('dashboard');
            $table->dropColumn('booking_stats');
            $table->dropColumn('driver_availability_stats');
            $table->dropColumn('corporates');
            $table->dropColumn('users');
            $table->dropColumn('providers');
            $table->dropColumn('sub_admins');
            $table->dropColumn('ride_requests_management');
            $table->dropColumn('vehicle_types');
            $table->dropColumn('promo_codes');
            $table->dropColumn('rental_management');
            $table->dropColumn('airport_details');
            $table->dropColumn('destination_details');
            $table->dropColumn('pricing_management');
            $table->dropColumn('provider_ratings');
            $table->dropColumn('user_ratings');
            $table->dropColumn('documents_management');
            $table->dropColumn('currency_management');
            $table->dropColumn('transactions');
            $table->dropColumn('push_notifications');
            $table->dropColumn('settings');
        });
    }
}
