<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            ['name' => 'E-commerce', 'description' => 'Categories for online stores.'],
            ['name' => 'Blogs', 'description' => 'Categories for blogging websites.'],
            ['name' => 'Corporate', 'description' => 'Categories for business and corporate websites.'],
            ['name' => 'Educational', 'description' => 'Categories for schools and online learning platforms.'],
            ['name' => 'News', 'description' => 'Categories for news websites.'],
            ['name' => 'Portfolio', 'description' => 'Categories for portfolio websites.'],
            ['name' => 'Non-Profit', 'description' => 'Categories for non-profit organization websites.'],
            ['name' => 'Event', 'description' => 'Categories for event and ticketing websites.'],
            ['name' => 'Healthcare', 'description' => 'Categories for healthcare websites.'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'description' => $category['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
