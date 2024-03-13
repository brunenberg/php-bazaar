<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

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
            DB::table('users')->insert([
                'email' => $user['email'],
                'password' => bcrypt($user['password']),
                'user_type' => $user['user_type'],
                'language' => $user['language'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->call(TemplateSeeder::class);
    }
}
