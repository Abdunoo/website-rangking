<?php

use App\Models\Contact;
use App\Models\Credit;
use App\Models\User;
use App\Models\Website;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'credits' => 1000
        ]);

        // Create Regular User
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'credits' => 200
        ]);

        $filePath = storage_path('app/top-1m.csv');
        $batchSize = 1000;
        $maxWebsites = 300;
        $websiteCount = 0;

        if (!file_exists($filePath)) {
            $this->command->error("File top-1m.csv not found in storage/app/");
            return;
        }

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            $this->command->error("Failed to open the file.");
            return;
        }

        $data = [];
        while (($line = fgetcsv($handle)) !== false) {
            $rank = $line[0];
            $domain = $line[1];


            $previousRank = $rank + rand(-1, 1);
            if ($rank <= 3) {
                $previousRank = $rank + rand(0, 3);
            }

            $website = Website::create([
                'rank' => $rank,
                'previous_rank' => $previousRank,
                'domain' => $domain,
                'name' => ucfirst(explode('.', $domain)[0]),
                'rating' => rand(0, 50) / 10,
                'category' => 'Uncategorized',
            ]);

            Contact::create([
                'website_id' => $website->id,
                'type' => 'email',
                'value' => 'contact' . $rank . '@' . $domain,
                'user_id' => rand(1, 2)
            ]);

            Contact::create([
                'website_id' => $website->id,
                'type' => 'phone',
                'value' => '+123456789' . rand(1000, 9999),
                'user_id' => rand(1, 2)
            ]);

            $websiteCount++;

            if (count($data) >= $batchSize) {
                DB::table('websites')->insert($data);
                $data = [];
                $this->command->info("Inserted $batchSize rows...");
            }

            if ($websiteCount >= $maxWebsites) {
                break;
            }
        }

        if (count($data) > 0) {
            DB::table('websites')->insert($data); // Masukkan sisa data jika ada
        }

        fclose($handle);
        $this->command->info("Selesai mengimpor data.");

        Credit::create([
            'user_id' => 2, // Admin
            'website_id' => 1, // Admin
            'amount' => 100,
            'description' => 'Initial credits'
        ]);

        Credit::create([
            'user_id' => 2, // Regular user
            'website_id' => 2, // Regular user
            'amount' => 100,
            'description' => 'Initial credits'
        ]);

        $websites = Website::all();

        foreach ($websites as $website) {
            // Randomly decide to add 1 or 2 reviews for each website
            $reviewsCount = rand(1, 2); // Randomly choose 1 or 2 reviews

            // Create reviews for the current website
            $reviews = [];
            for ($i = 0; $i < $reviewsCount; $i++) {
                // Randomly pick a user_id, assuming you have users in the users table
                $user_id = User::inRandomOrder()->first()->id;

                $reviews[] = [
                    'user_id' => $user_id,
                    'website_id' => $website->id, // Associate with the current website
                    'content' => $this->generateReviewContent(),
                    'rating' => rand(1, 5), // Random rating between 1 and 5
                    'is_approved' => (bool)rand(0, 1), // Randomly set approval status
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            // Insert the reviews into the database
            DB::table('reviews')->insert($reviews);
        };

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
            'The customer support is great!'
        ];

        return $contents[array_rand($contents)];
    }
}
