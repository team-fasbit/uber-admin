<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('admins')->truncate();
      DB::table('admins')->insert([
          'name' => 'SmartCar',
          'email' => 'admin@SmartCar.com',
          'password' => bcrypt('12345'),
          'is_activated' => 1,
          'role' => '1',
          'dashboard' => '3',
          'booking_stats' => '3',
          'driver_availability_stats' => '3',
          'corporates' => '1,2,3,4,10,11',
          'call_center_managers' => '1,2,3,4,10,11',
          'users' => '1,2,3,4,5,10,11',
          'providers' => '1,2,3,4,5,6,8,9,11',
          'sub_admins' => '1,2,3,4,10',
          'ride_requests_management' => '3,7,12,13',
          'vehicle_types' => '1,2,3,4',
          'promo_codes' => '1,2,3,4',
          'rental_management' => '1,2,3,4',
          'airport_details' => '1,2,3,4',
          'destination_details' => '1,2,3,4',
          'pricing_management' => '1,2,3,4',
          'provider_ratings' => '3,4',
          'user_ratings' => '3,4',
          'documents_management' => '1,2,3,4',
          'currency_management' => '1,2,3,4',
          'transactions' => '3',
          'push_notifications' => '3',
          'settings' => '3',
          'ads_management' => '1,2,3,4',
      ]);
    }
}
