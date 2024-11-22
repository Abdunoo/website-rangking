<?php

use App\Models\Website;
use App\Models\Contact;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    public function run()
    {
        // Example data for websites
        $categories = ['Technology', 'Business', 'Health', 'Education', 'Sports'];
        $domains = ['example.com', 'techhub.com', 'healthwise.com', 'eduportal.com', 'sportzone.com'];

        for ($i = 1; $i <= 100; $i++) {
            $website = Website::create([
                'domain' => $domains[array_rand($domains)],
                'category' => $categories[array_rand($categories)],
                'rank' => rand(1, 1000),
                'rank_change' => rand(-10, 10),
            ]);

            // Add two contacts for each website
            Contact::create([
                'website_id' => $website->id,
                'type' => 'email',
                'value' => 'contact' . $i . '@' . $website->domain,
                'user_id' => rand(1, 2) // Assuming user IDs 1 and 2 exist
            ]);

            Contact::create([
                'website_id' => $website->id,
                'type' => 'phone',
                'value' => '+123456789' . rand(1000, 9999),
                'user_id' => rand(1, 2)
            ]);
        }
    }
}
