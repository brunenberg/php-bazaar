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
            ['title' => 'Sony Camera', 'description' => 'Camera is in uitstekende staat, perfect voor hobbyfotografen en professionals. Wordt geleverd met extra lenzen en draagtas. Ophalen of verzenden mogelijk.', 'image' => 'sony_camera.jpg', 'company_id' => 1, 'user_id' => null, 'bidding_allowed' => 0, 'price' => rand(2, 200) * 0.5, 'type' => 'verhuur', 'rental_days' => '5', 'condition' => '100', 'wear_speed' => 'normal', 'active' => true],
            ['title' => 'Vintage Leren Bank', 'description' => 'Prachtige vintage leren bank in donkerbruin. Comfortabel en stijlvol, perfect voor elk interieur. Afmetingen: 220cm x 90cm x 80cm.', 'image' => 'vintage_couch.jpeg', 'user_id' => 3, 'company_id' => null, 'bidding_allowed' => rand(0, 1), 'price' => rand(2, 200) * 0.5],
            ['title' => 'MacBook Pro 2020', 'description' => 'Zo goed als nieuwe MacBook Pro 2020 te koop. Slechts enkele maanden oud en nauwelijks gebruikt. Inclusief oplader en originele doos.', 'image' => 'macbook.jpeg', 'company_id' => null, 'user_id' => 3, 'bidding_allowed' => rand(0, 1), 'price' => rand(2, 200) * 0.5, 'active' => true],
            ['title' => 'Mountainbike', 'description' => 'Specialized mountainbike te koop. Maat M, geschikt voor avontuurlijke ritten in de natuur. Inclusief accessoires zoals fietshelm en fietscomputer.', 'image' => 'mountainbike.jpg', 'company_id' => 1, 'user_id' => null, 'bidding_allowed' => 0, 'price' => rand(2, 200) * 0.5, 'type' => 'verhuur', 'rental_days' => '5', 'condition' => '100', 'wear_speed' => 'normal'],
            ['title' => 'Antieke Klok', 'description' => 'Mooie antieke staande klok te koop. Functioneert perfect en voegt een vleugje elegantie toe aan elke kamer. Hoogte: 180cm.', 'image' => 'antieke_klok.jpg', 'company_id' => 1, 'user_id' => null, 'bidding_allowed' => rand(0, 1), 'price' => rand(2, 200) * 0.5],
            ['title' => 'Design Eettafel', 'description' => 'Moderne design eettafel met glazen blad en metalen poten. Afmetingen: 150cm x 90cm x 75cm. Past perfect in een eigentijdse eetkamer.', 'image' => 'design_eettafel.jpeg','company_id' => null, 'user_id' => 3, 'bidding_allowed' => rand(0, 1), 'price' => rand(2, 200) * 0.5, 'active' => true],
            ['title' => 'Vintage Platenspeler', 'description' => 'Prachtige vintage platenspeler te koop. Volledig functioneel en perfect voor liefhebbers van vinyl. Inclusief enkele LPs.', 'image' => 'platenspeler.jpg', 'company_id' => 1, 'user_id' => null, 'bidding_allowed' => rand(0, 1), 'price' => rand(2, 200) * 0.5],
            ['title' => 'Digitale Piano', 'description' => 'Yamaha digitale piano te koop. Uitstekende geluidskwaliteit en aanslaggevoelige toetsen. Ideaal voor beginners en gevorderden.', 'image' => 'piano.jpeg','company_id' => null, 'user_id' => 3, 'bidding_allowed' => rand(0, 1), 'price' => rand(2, 200) * 0.5],
            ['title' => 'Leren Aktetas', 'description' => 'Stijlvolle leren aktetas te koop. Ideaal voor zakelijk gebruik of dagelijks gebruik. Veel vakken voor het opbergen van essentials.', 'image' => 'leren_tas.jpeg', 'company_id' => 1, 'user_id' => null, 'bidding_allowed' => rand(0, 1), 'price' => rand(2, 200) * 0.5],
            // Extra listings
            ['title' => 'Vintage Fiets', 'description' => 'Een klassieke vintage fiets in goede staat. Perfect voor stadsritten of om toe te voegen aan je collectie.', 'image' => 'vintage_fiets.jpg', 'company_id' => null, 'user_id' => 3, 'bidding_allowed' => 0, 'price' => rand(2, 200) * 0.5, 'type' => 'verhuur', 'rental_days' => '5', 'condition' => '100', 'wear_speed' => 'normal', 'active' => true],
            ['title' => 'Schilderij "Zonsondergang"', 'description' => 'Een prachtig handgeschilderd schilderij van een kleurrijke zonsondergang. Een echte blikvanger voor je huis of kantoor.', 'image' => 'schilderij_zonsondergang.jpg', 'company_id' => null, 'user_id' => 3, 'bidding_allowed' => rand(0, 1), 'price' => rand(2, 200) * 0.5],
            ['title' => 'Tuinmeubelset', 'description' => 'Een complete tuinmeubelset bestaande uit een tafel, stoelen en een parasol. Ideaal voor zomerse dagen in de tuin of op het terras.', 'image' => 'tuinmeubelset.jpg', 'company_id' => 1, 'user_id' => null, 'bidding_allowed' => rand(0, 1), 'price' => rand(2, 200) * 0.5],
            ['title' => 'Vintage Horloge', 'description' => 'Een prachtig vintage horloge met een uniek design. Een must-have voor horlogeliefhebbers en verzamelaars.', 'image' => 'vintage_horloge.jpg', 'company_id' => null, 'user_id' => 3, 'bidding_allowed' => rand(0, 1), 'price' => rand(2, 200) * 0.5, 'active' => true],
            ['title' => 'Kunstbloemenboeket', 'description' => 'Een levensecht kunstbloemenboeket dat het hele jaar door kleur en vreugde brengt. Geen onderhoud nodig!', 'image' => 'kunstbloemenboeket.jpg', 'company_id' => 1, 'user_id' => null, 'bidding_allowed' => rand(0, 1), 'price' => rand(2, 200) * 0.5, 'active' => true],
            ['title' => 'Gamingstoel', 'description' => 'Een comfortabele gamingstoel met ergonomisch ontwerp voor lange speelsessies. Inclusief verstelbare armleuningen en rugleuning.', 'image' => 'gamingstoel.jpg', 'company_id' => null, 'user_id' => 3, 'bidding_allowed' => rand(0, 1), 'price' => rand(2, 200) * 0.5],
            ['title' => 'Espressomachine', 'description' => 'Een hoogwaardige espressomachine voor het maken van de perfecte koffie thuis. Maak indruk op je gasten met barista-kwaliteit koffie.', 'image' => 'espressomachine.jpg', 'company_id' => 1, 'user_id' => null, 'bidding_allowed' => rand(0, 1), 'price' => rand(2, 200) * 0.5],
            ['title' => 'Vintage Koffer', 'description' => 'Een prachtige vintage koffer met een retro uitstraling. Ideaal voor reizen of als decoratief stuk in huis.', 'image' => 'vintage_koffer.jpg', 'company_id' => null, 'user_id' => 3, 'bidding_allowed' => rand(0, 1), 'price' => rand(2, 200) * 0.5],
            ['title' => 'Fitnessapparatuur', 'description' => 'Een complete set fitnessapparatuur voor thuisgebruik. Train je hele lichaam zonder de deur uit te hoeven.', 'image' => 'fitnessapparatuur.jpg', 'company_id' => 1, 'user_id' => null, 'bidding_allowed' => rand(0, 1), 'price' => rand(2, 200) * 0.5, 'active' => true],
        ];
        
        

        foreach ($listings as $listing) {
            Listing::create([
                'title' => $listing['title'],
                'description' => $listing['description'],
                'image' => $listing['image'],
                'company_id' => $listing['company_id'],
                'user_id' => $listing['user_id'],
                'bidding_allowed' => $listing['bidding_allowed'],
                'price' => $listing['price'],
                'type' => $listing['type'] ?? 'verkoop',
                'active' => $listing['active'] ?? false,
                'rental_days' => $listing['rental_days'] ?? null,
                'condition' => $listing['condition'] ?? null,
                'wear_speed' => $listing['wear_speed'] ?? null,
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
