<?php

use App\Models\Credit;
use Illuminate\Database\Seeder;

class CreditSeeder extends Seeder
{
    public function run()
    {
        Credit::create([
            'user_id' => 1, // Admin
            'amount' => 500,
            'description' => 'Initial credits'
        ]);

        Credit::create([
            'user_id' => 2, // Regular user
            'amount' => 200,
            'description' => 'Initial credits'
        ]);
    }
}
