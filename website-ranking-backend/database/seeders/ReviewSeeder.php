<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Website;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $websites = Website::all();

        foreach ($websites as $website) {
            $reviews = [];
            $reviewsCount = rand(1, 2); // Randomly generate 1 or 2 reviews per website

            for ($i = 0; $i < $reviewsCount; $i++) {
                $userId = User::inRandomOrder()->first()->id; // Random user ID
                $reviews[] = [
                    'user_id' => $userId,
                    'website_id' => $website->id,
                    'content' => $this->generateReviewContent(),
                    'rating' => rand(1, 5), // Random rating between 1 and 5
                    'is_approved' => (bool)rand(0, 1), // Randomly set approval status
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            // Bulk insert reviews for the current website
            DB::table('reviews')->insert($reviews);
        }
    }

    private function generateReviewContent()
    {
        $contents = [
            'This is a great website!',
            'Needs some improvements.',
            'Excellent user experience.',
            'Not bad, but could be better.',
            'I love the design!',
            'Very slow and buggy.',
            'Couldn\'t find what I was looking for.',
            'The customer support is great!',
        ];

        return $contents[array_rand($contents)];
    }
}
