<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SwitchLanguageTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testLanguage(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/account')
                    ->select('locale', 'en')
                    ->press('select-locale')
                    ->assertSee('Language')
                    ->select('locale', 'nl')
                    ->press('select-locale')
                    ->assertSee('Taal');
        });
    }
}
