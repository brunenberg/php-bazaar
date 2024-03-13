<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Company;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [
            ['email' => 'zakelijk@gmail.com', 'password' => 'zakelijk', 'user_type' => 'zakelijke_verkoper', 'language' => 'nl'],
            ['email' => 'gebruiker@gmail.com', 'password' => 'gebruiker', 'user_type' => 'gebruiker', 'language' => 'nl'],
            ['email' => 'particulier@gmail.com', 'password' => 'particulier', 'user_type' => 'particuliere_verkoper', 'language' => 'nl'],
        ];

        foreach ($users as $user) {
            $userId = DB::table('users')->insertGetId([
                'email' => $user['email'],
                'password' => bcrypt($user['password']),
                'user_type' => $user['user_type'],
                'language' => $user['language'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        
            if ($user['user_type'] === 'zakelijke_verkoper') {
                $company = new Company();
                $company->user_id = $userId;
                $company->save();
            }
        }

        $faker = \Faker\Factory::create();

        $listings = [
            ['title' => 'Listing 1', 'description' => $faker->text, 'tags' => 'tag1,tag2,tag3'],
            ['title' => 'Listing 2', 'description' => $faker->text, 'tags' => 'tag1,tag2'],
            ['title' => 'Listing 3', 'description' => $faker->text, 'tags' => 'tag1,tag3'],
            ['title' => 'Listing 4', 'description' => $faker->text, 'tags' => 'tag1,tag2,tag3'],
            ['title' => 'Listing 5', 'description' => $faker->text, 'tags' => 'tag1,tag2'],
            ['title' => 'Listing 6', 'description' => $faker->text, 'tags' => 'tag1,tag3'],
            ['title' => 'Listing 7', 'description' => $faker->text, 'tags' => 'tag1,tag2,tag3'],
            ['title' => 'Listing 8', 'description' => $faker->text, 'tags' => 'tag1,tag2'],
            ['title' => 'Listing 9', 'description' => $faker->text, 'tags' => 'tag1,tag3'],
        ];

        foreach ($listings as $listing) {
            DB::table('listings')->insert([
                'title' => $listing['title'],
                'description' => $listing['description'],
                'tags' => $listing['tags'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        
        $this->call(TemplateSeeder::class);
    }
}
