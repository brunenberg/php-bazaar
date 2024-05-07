<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ReviewTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testPlaceReviewListing(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->LoginAs(User::find(2))
                    ->visit('/listing/1')
                    ->assertSee('Plaats een recensie')
                    ->select('rating', 4)
                    ->type('review', 'Dit is een test recensie!')
                    ->press('#submit_review')
                    ->assertSee('Dit is een test recensie!');
        });
    }

    public function testPlaceReviewAdvertiser(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->LoginAs(User::find(2))
                    ->visit('/company/winkel1')
                    ->assertSee('Schrijf een beoordeling')
                    ->select('rating', 4)
                    ->type('review', 'Dit is een test recensie!')
                    ->press('#submit_review')
                    ->assertSee('Dit is een test recensie!');
        });
    }
}
