<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        // Seed Reviews
        $this->seedReviews();

        // Seed Settings
        $this->seedSettings();
    }

    private function seedReviews()
    {
        $reviews = [
            [
                'user_id' => 1, // Assuming you have users in the users table
                'website_id' => 1, // Assuming you have websites in the websites table
                'content' => 'This is a great website!',
                'rating' => 5,
                'is_approved' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'user_id' => 2,
                'website_id' => 1,
                'content' => 'Needs some improvements',
                'rating' => 3,
                'is_approved' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            // Add more reviews as needed
        ];

        DB::table('reviews')->insert($reviews);
    }

    private function seedSettings()
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'My Awesome Website',
                'type' => 'string',
                'is_public' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'is_public' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'key' => 'max_upload_size',
                'value' => '10240', // 10MB in KB
                'type' => 'integer',
                'is_public' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            // Add more settings as needed
        ];

        DB::table('settings')->insert($settings);
    }
}
