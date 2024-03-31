<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Listing;

class CheckExpiredListings extends Command
{
    protected $signature = 'check:expired-listings';
    protected $description = 'Check if listings have expired';

    public function handle(){

        $listings = Listing::where('expires_in_days', '>', 0)->get();

        foreach($listings as $listing){
            $listing->expires_in_days -= 1;
            $listing->save();
        }

        $listings = Listing::where('expires_in_days', 0)->get();
        foreach($listings as $listing){
            $listing->active = false;
            $listing->save();
        }
    }
}
