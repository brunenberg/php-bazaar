<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['email' => 'zakelijk@gmail.com', 'password' => 'zakelijk', 'user_type' => 'zakelijke_verkoper', 'language' => 'nl'],
            ['email' => 'gebruiker@gmail.com', 'password' => 'gebruiker', 'user_type' => 'gebruiker', 'language' => 'nl'],
            ['email' => 'particulier@gmail.com', 'password' => 'particulier', 'user_type' => 'particuliere_verkoper', 'language' => 'nl'],
            ['email' => 'eigenaar@gmail.com', 'password' => 'eigenaar', 'user_type' => 'admin', 'language' => 'nl'],
        ];

        foreach ($users as $user) {
            $newUser = User::create([
                'email' => $user['email'],
                'password' => bcrypt($user['password']),
                'user_type' => $user['user_type'],
                'language' => $user['language'],
            ]);

            if ($user['user_type'] === 'zakelijke_verkoper') {
                $company = Company::create([
                    'user_id' => $newUser->id,
                    'name' => 'De winkel van ' . $newUser->id,
                    'description' => 'Dit is een beschrijving van bedrijf ' . $newUser->id . '. Hier koop je de leukste spullen!',
                    'image' => 'company.jpg',
                    'slug' => 'winkel' . $newUser->id,
                    'background_color' => '#47ffd1',
                    'text_color' => '#000000',
                ]);
                $company->save();


                // Add templates to company (Table: company_template - company_id, template_id, order)
                $company->templates()->attach(['template_id' => 2], ['order' => 0, 'company_id' => $company->id]);
                $company->templates()->attach(['template_id' => 1], ['order' => 1, 'company_id' => $company->id]);
                
            }
        }
    }
}
