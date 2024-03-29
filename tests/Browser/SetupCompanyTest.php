<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SetupCompanyTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testSetupCompanyInfo(): void
    {
        $this->browse(function (Browser $browser) {

            $browser->loginAs(User::find(1))
                    ->visit('/account')
                    ->assertSee('Instellingen winkelpagina')
                    ->type('winkelnaam', 'De Test Winkel!')
                    ->type('winkelbeschrijving', 'Dit is de winkelbeschrijving!')
                    ->type('slug', 'test')
                    ->type('achtergrondkleur', '#fcba03')
                    ->type('tekstkleur', '#000000')
                    ->press('#save_page_settings')
                    ->visit('/company/test')
                    ->assertSee('De Test Winkel!');
        });
    }
}
