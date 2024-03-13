<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['email' => 'zakelijk@gmail.com', 'password' => 'zakelijk', 'user_type' => 'zakelijke_verkoper', 'language' => 'nl'],
            ['email' => 'gebruiker@gmail.com', 'password' => 'gebruiker', 'user_type' => 'gebruiker', 'language' => 'nl'],
            ['email' => 'particulier@gmail.com', 'password' => 'particulier', 'user_type' => 'particuliere_verkoper', 'language' => 'nl'],
        ];

        foreach ($users as $user) {
            $newUser = User::create([
                'email' => $user['email'],
                'password' => bcrypt($user['password']),
                'user_type' => $user['user_type'],
                'language' => $user['language'],
            ]);

            if ($user['user_type'] === 'zakelijke_verkoper') {
                $company = new Company();
                $company->user_id = $newUser->id;
                $company->save();
            }
        }
    }
}
