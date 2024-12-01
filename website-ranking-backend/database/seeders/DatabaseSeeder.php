<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
            WebsiteSeeder::class,
            CreditSeeder::class,
            SettingSeeder::class,
            ReviewSeeder::class,
            WebsiteTrendsSeeder::class,
        ]);
    }
}
