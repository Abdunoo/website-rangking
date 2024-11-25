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
            'credits' => 500
        ]);

        $filePath = storage_path('app/top-1m.csv');
        $batchSize = 1000;
        $maxWebsites = 300; // Maksimal jumlah website yang akan diimpor
        $websiteCount = 0; // Penghitung jumlah website yang diimpor

        if (!file_exists($filePath)) {
            $this->command->error("File top-1m.csv tidak ditemukan di storage/app/");
            return;
        }

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            $this->command->error("Gagal membuka file.");
            return;
        }

        $data = [];
        while (($line = fgetcsv($handle)) !== false) {
            $rank = $line[0];
            $domain = $line[1];

            $website = Website::create([
                'rank' => $rank,
                'previous_rank' => $rank,
                'domain' => $domain,
                'name' => ucfirst(explode('.', $domain)[0]),
                'rating' => rand(0, 50) / 10, // Generate a random rating between 0.0 and 5.0
                'category' => 'Uncategorized',
            ]);

            // Tambahkan dua kontak untuk setiap website
            Contact::create([
                'website_id' => $website->id,
                'type' => 'email',
                'value' => 'contact' . $rank . '@' . $domain,
                'user_id' => rand(1, 2) // Asumsi user_id 1 dan 2 ada
            ]);

            Contact::create([
                'website_id' => $website->id,
                'type' => 'phone',
                'value' => '+123456789' . rand(1000, 9999),
                'user_id' => rand(1, 2)
            ]);

            $websiteCount++; // Increment penghitung

            if (count($data) >= $batchSize) {
                DB::table('websites')->insert($data);
                $data = [];
                $this->command->info("Memasukkan $batchSize baris...");
            }

            // Hentikan jika sudah mencapai jumlah maksimum
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
            'user_id' => 1, // Admin
            'amount' => 500,
            'description' => 'Initial credits'
        ]);

        Credit::create([
            'user_id' => 2, // Regular user
            'amount' => 200,
            'description' => 'Initial credits'
        ]); {
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
}
