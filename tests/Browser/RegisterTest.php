<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testExample(): void
    {
        $this->browse(function (Browser $first, Browser $second, Browser $third) {
            $first->visit('/register')
                    ->assertSee('Registreren')
                    ->type('email', 'zakelijk2@gmail.com')
                    ->type('password', 'zakelijk')
                    ->select('user_type', 'zakelijke_verkoper')
                    ->press('Registreren')
                    ->assertPathIs('/');

            $second->visit('/register')
                    ->assertSee('Registreren')
                    ->type('email', 'particulier2@gmail.com')
                    ->type('password', 'particulier')
                    ->select('user_type', 'particuliere_verkoper')
                    ->press('Registreren')
                    ->assertPathIs('/');
            
            $third->visit('/register')
                    ->assertSee('Registreren')
                    ->type('email', 'gebruiker2@gmail.com')
                    ->type('password', 'gebruiker')
                    ->select('user_type', 'gebruiker')
                    ->press('Registreren')
                    ->assertPathIs('/');
        });
    }
}
