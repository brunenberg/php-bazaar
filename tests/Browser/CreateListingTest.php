<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateListingTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testCreateListing(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(3))
                    ->visit('listings')
                    ->press('#create_new')
                    ->type('title', 'Test Advertentie')
                    ->type('description', 'Dit is een test advertentie!')
                    ->select('type', 'verhuur')
                    ->attach('image', storage_path('app\public\images\logo_no_bg.png'))
                    ->press('#save_listing')
                    ->assertSee('Test Advertentie');
        });
    }
}
