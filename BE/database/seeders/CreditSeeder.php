<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Credit;
use App\Helpers\DataHelper;

class CreditSeeder extends Seeder
{
    public function run()
    {
        Credit::create([
            'user_id' => 1, // Admin
            'website_id' => 1, // Admin's website
            'amount' => 100,
            'description' => 'Initial credits',
            'type' => DataHelper::PURCHASE,
            'status' => DataHelper::APPROVED,
        ]);

        Credit::create([
            'user_id' => 2, // Regular user
            'website_id' => 2, // Regular user's website
            'amount' => 100,
            'description' => 'Initial credits',
            'type' => DataHelper::PURCHASE,
            'status' => DataHelper::APPROVED,
        ]);
    }
}
