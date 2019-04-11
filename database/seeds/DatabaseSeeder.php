<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(SettingstableSeeder::class);
        $this->call(ServiceTypeSeeder::class);
        $this->call(MobileRegisterSeeder::class);
        $this->call(CorporateSeeder::class);
        $this->call(HourlyPackageSeeder::class);
        $this->call(adminTronSettings::class);
        $this->call(TronWalletPaymentMode::class);
    }
}
