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
            ['title' => 'Listing 1', 'description' => 'This is a great listing.', 'tags' => 'tag1,tag2,tag3'],
            ['title' => 'Listing 2', 'description' => 'This is another great listing.', 'tags' => 'tag1,tag2'],
            ['title' => 'Listing 3', 'description' => 'This is yet another great listing.', 'tags' => 'tag1,tag3'],
            ['title' => 'Listing 4', 'description' => 'This is a great listing.', 'tags' => 'tag1,tag2,tag3'],
            ['title' => 'Listing 5', 'description' => 'This is another great listing.', 'tags' => 'tag1,tag2'],
            ['title' => 'Listing 6', 'description' => 'This is yet another great listing.', 'tags' => 'tag1,tag3'],
            ['title' => 'Listing 7', 'description' => 'This is a great listing.', 'tags' => 'tag1,tag2,tag3'],
            ['title' => 'Listing 8', 'description' => 'This is another great listing.', 'tags' => 'tag1,tag2'],
            ['title' => 'Listing 9', 'description' => 'This is yet another great listing.', 'tags' => 'tag1,tag3'],
        ];

        foreach ($listings as $listing) {
            Listing::create([
                'title' => $listing['title'],
                'description' => $listing['description'],
                'tags' => $listing['tags'],
            ]);
        }
    }
}
