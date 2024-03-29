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
            ['title' => 'Sony Camera', 'description' => 'Camera is in uitstekende staat, perfect voor hobbyfotografen en professionals. Wordt geleverd met extra lenzen en draagtas. Ophalen of verzenden mogelijk.', 'image' => 'sony_camera.jpg', 'company_id' => 1, 'user_id' => null],
            ['title' => 'Vintage Leren Bank', 'description' => 'Prachtige vintage leren bank in donkerbruin. Comfortabel en stijlvol, perfect voor elk interieur. Afmetingen: 220cm x 90cm x 80cm.', 'image' => 'vintage_couch.jpeg', 'user_id' => 3, 'company_id' => null],
            ['title' => 'MacBook Pro 2020', 'description' => 'Zo goed als nieuwe MacBook Pro 2020 te koop. Slechts enkele maanden oud en nauwelijks gebruikt. Inclusief oplader en originele doos.', 'image' => 'macbook.jpeg', 'company_id' => null, 'user_id' => 3],
            ['title' => 'Mountainbike', 'description' => 'Specialized mountainbike te koop. Maat M, geschikt voor avontuurlijke ritten in de natuur. Inclusief accessoires zoals fietshelm en fietscomputer.', 'image' => 'mountainbike.jpg', 'company_id' => 1, 'user_id' => null],
            ['title' => 'Antieke Klok', 'description' => 'Mooie antieke staande klok te koop. Functioneert perfect en voegt een vleugje elegantie toe aan elke kamer. Hoogte: 180cm.', 'image' => 'antieke_klok.jpg', 'company_id' => 1, 'user_id' => null],
            ['title' => 'Design Eettafel', 'description' => 'Moderne design eettafel met glazen blad en metalen poten. Afmetingen: 150cm x 90cm x 75cm. Past perfect in een eigentijdse eetkamer.', 'image' => 'design_eettafel.jpeg','company_id' => null, 'user_id' => 3],
            ['title' => 'Vintage Platenspeler', 'description' => 'Prachtige vintage platenspeler te koop. Volledig functioneel en perfect voor liefhebbers van vinyl. Inclusief enkele LPs.', 'image' => 'platenspeler.jpg', 'company_id' => 1, 'user_id' => null],
            ['title' => 'Digitale Piano', 'description' => 'Yamaha digitale piano te koop. Uitstekende geluidskwaliteit en aanslaggevoelige toetsen. Ideaal voor beginners en gevorderden.', 'image' => 'piano.jpeg','company_id' => null, 'user_id' => 3],
            ['title' => 'Leren Aktetas', 'description' => 'Stijlvolle leren aktetas te koop. Ideaal voor zakelijk gebruik of dagelijks gebruik. Veel vakken voor het opbergen van essentials.', 'image' => 'leren_tas.jpeg', 'company_id' => 1, 'user_id' => null],
        ];

        foreach ($listings as $listing) {
            Listing::create([
                'title' => $listing['title'],
                'description' => $listing['description'],
                'image' => $listing['image'],
                'company_id' => $listing['company_id'],
                'user_id' => $listing['user_id'],
            ]);
        }

        // Write reviews for listings
        $listings = Listing::all();
        foreach ($listings as $listing) {
            $listing->reviews()->attach(2, ['review' => 'Geweldig product, precies zoals beschreven!', 'rating' => 5]);
            $listing->reviews()->attach(3, ['review' => 'Snelle levering en goede service.', 'rating' => 4]);
            $listing->reviews()->attach(4, ['review' => 'Mooi product, maar duurder dan verwacht.', 'rating' => 3]);
        }
    }
}
