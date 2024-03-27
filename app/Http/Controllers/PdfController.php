<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function generate()
    {
        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML('<h1>Test</h1>'); // Hier kun je de HTML van je PDF genereren
        return $pdf->stream()->with('success', 'PDF generated successfully.');
    }
}
