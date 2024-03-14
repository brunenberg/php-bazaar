<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function show($id)
    {
        $listing = Listing::find($id);
        if (!$listing) {
            abort(404);
        }
        return view('listing', compact('listing'));
    }

    public function addReview(Request $request)
    {
        $validatedData = $request->validate([
            'review' => 'required|string',
            'rating' => 'required|numeric|min:1|max:5',
            'listing_id' => 'required|numeric'
        ]);

        $listing = Listing::find($validatedData['listing_id']);

        $existingReview = $listing->reviews()->where('user_id', auth()->user()->id)->first();
        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already placed a review for this listing.');
        }

        $listing->reviews()->attach(auth()->user()->id, [
            'review' => $validatedData['review'],
            'rating' => $validatedData['rating']
        ]);

        return redirect()->back();
    }

    public function deleteReview(Request $request)
    {
        $validatedData = $request->validate([
            'listing_id' => 'required|numeric',
        ]);

        $listing = Listing::find($validatedData['listing_id']);
        $listing->reviews()->detach(auth()->user()->id);

        return redirect()->back();
    }
}
