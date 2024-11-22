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
        
        // Create more realistic domains and website names
        $businessWords = [
            'tech', 'hub', 'lab', 'solutions', 'ventures', 'group', 'systems', 'digital', 'world', 'inc', 'network',
            'consulting', 'global', 'media', 'innovations', 'partners', 'corp', 'analytics', 'apps', 'edge', 'ideas'
        ];
        
        $tlds = ['.com', '.net', '.org', '.io', '.co', '.ai', '.tech', '.biz', '.org'];
        
        // Generate 100 different domains and website names
        for ($i = 1; $i <= 100; $i++) {
            // Generate realistic domain name
            $domain = $businessWords[array_rand($businessWords)] . rand(1, 99) . $tlds[array_rand($tlds)];
            
            // Website name can be based on the domain name (removing TLD and making it more readable)
            $websiteName = ucfirst(str_replace(['.com', '.net', '.org', '.io', '.co', '.ai', '.tech', '.biz'], '', $domain));
            
            // Create a website entry
            $website = Website::create([
                'domain' => $domain,
                'name' => $websiteName,  // Assign the website name
                'category' => $categories[array_rand($categories)],  // Random category
                'rank' => rand(1, 1000),  // Random rank
                'rank_change' => rand(-10, 10),  // Random rank change
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
