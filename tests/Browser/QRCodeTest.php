<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class QRCodeTest extends DuskTestCase
{
    
    public function testQRCode(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('/listing/1')
                    ->assertSee('Deel deze advertentie')
                    ->assertVisible('img[alt="QR Code"]')
                    ->visit('/listing/2')
                    ->assertSee('Deel deze advertentie')
                    ->assertVisible('img[alt="QR Code"]')
                    ->visit('/listing/3')
                    ->assertSee('Deel deze advertentie')
                    ->assertVisible('img[alt="QR Code"]');
        });
    }
}
