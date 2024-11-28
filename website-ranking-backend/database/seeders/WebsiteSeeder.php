<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Website;
use App\Models\Contact;

class WebsiteSeeder extends Seeder
{
    public function run()
    {
        $filePath = storage_path('app/top-1m.csv');
        $maxWebsites = 10000;

        if (!file_exists($filePath)) {
            $this->command->error("File top-1m.csv not found in storage/app/");
            return;
        }

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            $this->command->error("Failed to open the file.");
            return;
        }

        $websiteCount = 0;

        while (($line = fgetcsv($handle)) !== false && $websiteCount < $maxWebsites) {
            $rank = $line[0];
            $domain = $line[1];
            $categoryId = rand(1, 9); // Random category ID between 1 and 9

            $website = Website::create([
                'rank' => $rank,
                'previous_rank' => $rank + rand(-1, 1),
                'domain' => $domain,
                'name' => ucfirst(explode('.', $domain)[0]),
                'rating' => rand(0, 50) / 10,
                'category_id' => $categoryId,
            ]);

            Contact::create([
                'website_id' => $website->id,
                'type' => 'email',
                'value' => 'contact' . $rank . '@' . $domain,
                'user_id' => rand(1, 2),
            ]);

            Contact::create([
                'website_id' => $website->id,
                'type' => 'phone',
                'value' => '+123456789' . rand(1000, 9999),
                'user_id' => rand(1, 2),
            ]);

            $websiteCount++;
        }

        fclose($handle);
        $this->command->info("Inserted $websiteCount websites.");
    }
}
