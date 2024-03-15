<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Response;

class ListingController extends Controller
{
    public function show($id)
    {
        $listing = Listing::find($id);
        if (!$listing) {
            abort(404);
        }
        $qrCode = $this->qr($listing);
        return view('listing', compact('listing', 'qrCode'));
    }

    public function qr($listing)
    {
        $qrPath = public_path('qr-codes/' . $listing->id . '.svg');
        $qrUrl = asset('qr-codes/' . $listing->id . '.svg');

        if (!file_exists($qrPath)) {
            QrCode::size(300)->generate(route('listing.show', $listing), $qrPath);
        }
        
        return $qrUrl;
    }
}