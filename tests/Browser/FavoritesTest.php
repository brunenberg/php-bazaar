<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FavoritesTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testFavorites(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(2))
                    ->visit('/listing/1')
                    ->press('add_favorite')
                    ->visit('/account')
                    ->press('remove_favorite')
                    ->assertSee('geen favoriete advertenties');
        });
    }
}
