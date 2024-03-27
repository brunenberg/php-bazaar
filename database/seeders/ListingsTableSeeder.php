<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Listing;

class ListingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $listings = [
            ['title' => 'Listing 1', 'description' => 'This is a great listing.', 'image' => '1710329038.jpeg', 'company_id' => 1],
            ['title' => 'Listing 2', 'description' => 'This is a great listing.', 'image' => '1710329038.jpeg', 'company_id' => 1],
            ['title' => 'Listing 3', 'description' => 'This is a great listing.', 'image' => '1710329038.jpeg', 'company_id' => 1],
            ['title' => 'Listing 4', 'description' => 'This is a great listing.', 'image' => '1710329038.jpeg', 'company_id' => 1],
            ['title' => 'Listing 5', 'description' => 'This is a great listing.', 'image' => '1710329038.jpeg', 'company_id' => 1],
            ['title' => 'Listing 6', 'description' => 'This is a great listing.', 'image' => '1710329038.jpeg', 'company_id' => 1],
            ['title' => 'Listing 7', 'description' => 'This is a great listing.', 'image' => '1710329038.jpeg', 'company_id' => 1],
            ['title' => 'Listing 8', 'description' => 'This is a great listing.', 'image' => '1710329038.jpeg', 'company_id' => 1],
            ['title' => 'Listing 9', 'description' => 'This is a great listing.', 'image' => '1710329038.jpeg', 'company_id' => 1],
            ['title' => 'Listing 10', 'description' => 'This is a great listing.', 'image' => '1710329038.jpeg', 'company_id' => 1],
        ];

        foreach ($listings as $listing) {
            Listing::create([
                'title' => $listing['title'],
                'description' => $listing['description'],
                'image' => $listing['image'],
                'company_id' => $listing['company_id'],
            ]);
        }
    }
}
