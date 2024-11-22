<?php

use App\Models\Contact;
use App\Models\Credit;
use App\Models\User;
use App\Models\Website;
use Illuminate\Database\Seeder;
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

            if (count($data) >= $batchSize) {
                DB::table('websites')->insert($data);
                $data = [];
                $this->command->info("Memasukkan $batchSize baris...");
            }
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
        ]);

        // // Call other seeders
        // $this->call([
        //     WebsiteSeeder::class,  // Assuming WebsiteSeeder exists and is correctly implemented
        //     CreditSeeder::class,   // Assuming CreditSeeder exists and is correctly implemented
        // ]);
    }
}
