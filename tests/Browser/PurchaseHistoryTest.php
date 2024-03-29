<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PurchaseHistoryTest extends DuskTestCase
{
    // Hier wordt ook getest of het linken van advertenties werkt (Winkelwagensysteem)
    public function testPurchaseHistory(): void
    {
        $this->browse(function (Browser $browser) {
            $listing_title_1 = '';
            $listing_title_2 = '';
        
            $browser->loginAs(User::find(2))
                    ->visit('/listing/1')
                    ->waitFor('h1')
                    ->tap(function ($browser) use (&$listing_title_1) {
                        $listing_title_1 = $browser->text('h1');
                    })
                    ->press('#add_to_cart')
                    ->visit('/listing/2')
                    ->waitFor('h1')
                    ->tap(function ($browser) use (&$listing_title_2) {
                        $listing_title_2 = $browser->text('h1');
                    })
                    ->press('#add_to_cart')
                    ->visit('/cart')
                    ->assertSee($listing_title_1)
                    ->assertSee($listing_title_2)
                    ->press('#checkout')
                    ->visit('/account')
                    ->assertSee($listing_title_1)
                    ->assertSee($listing_title_2)
                    ->screenshot('purchase-history');
        });
    }
}
