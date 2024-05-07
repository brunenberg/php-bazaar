<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Template::factory()->create([
            'name' => 'description',
            'description' => 'Description of the company',
        ]);

        Template::factory()->create([
            'name' => 'image',
            'description' => 'Image of the company',
        ]);

        Template::factory()->create([
            'name' => 'featured',
            'description' => 'Featured listings of the company',
        ]);
    }
}