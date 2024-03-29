<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PdfTest extends DuskTestCase
{
    
    public function testPdf(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(4))
                    ->visit('/company/winkel1')
                    ->press('#downloadContract');

            // Ga naar de map waar de download is opgeslagen en kijk of het pdf bestand betstaat
            
        });
    }
}
