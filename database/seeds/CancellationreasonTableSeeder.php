<?php

use Illuminate\Database\Seeder;

class CancellationreasonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('cancellation_reasons')->truncate();
      DB::table('cancellation_reasons')->insert([
          'cancel_reason' => 'Other',
          'cancel_fee' => 2,
          'status' => 1,
      ]);
    }
}
