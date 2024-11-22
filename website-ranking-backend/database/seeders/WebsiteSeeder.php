<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Website;
use App\Models\Contact;

class WebsiteSeeder extends Seeder
{
    public function run()
    {
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
    }
}
