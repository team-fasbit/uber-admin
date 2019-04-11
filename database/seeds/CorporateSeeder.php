<?php

use Illuminate\Database\Seeder;

class CorporateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('corporates')->truncate();
      DB::table('corporates')->insert([
          'name' => 'Admin Corporate',
          'email' => 'corporate@smartcar.com',
          'password' => bcrypt('12345'),
          'is_activated' => 1,
          'is_approved' => 1,
      ]);
    }
}
