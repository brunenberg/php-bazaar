<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/account')
                    ->assertSee('Inloggen')
                    ->type('email', 'gebruiker@gmail.com')
                    ->type('password', 'gebruiker')
                    ->press('Inloggen')
                    ->assertPathIs('/account');
        });
    }
}
