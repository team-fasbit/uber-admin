<?php

use Illuminate\Database\Seeder;

class HourlyPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('hourly_packages')->truncate();
      DB::table('hourly_packages')->insert([
          'number_hours' => 1,
          'price' => 10,
          'distance' => 10,
          'car_type_id' => 1,
          'status' => 1,
      ]);
    }
}
