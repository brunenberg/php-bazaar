<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function addBid(Request $request)
    {
        // Check if user is owner of listing
        if (auth()->user()->listings->contains($request->listing_id)) {
            return back()->with('error', 'You can not place bid on your own listing.');
        }

        // Check if user has placed 3 bids on whole platform
        if (auth()->user()->bids->count() >= 4) {
            return back()->with('error', 'You can only place 4 bids at a time.');
        }


        $request->validate([
            'bid' => 'required|numeric',
            'listing_id' => 'required|exists:listings,id'
        ]);

        $bid = new Bid;
        $bid->bid = $request->bid;
        $bid->listing_id = $request->listing_id;
        $bid->user_id = auth()->id();
        $bid->save();
        
        return back()->with('success', 'Bid added successfully.');
    }

    public function deleteBid(Request $request)
    {
        $request->validate([
            'bid_id' => 'required|exists:bids,id'
        ]);
        $bid = Bid::find($request->bid_id);
        $bid->delete();
        return back()->with('success', 'Bid deleted successfully.');
    }

    public function acceptBid(Request $request)
    {
        $request->validate([
            'bid_id' => 'required|exists:bids,id'
        ]);
        $bid = Bid::find($request->bid_id);
        $bid->accepted = true;
        $bid->save();

        $bid->listing->bought()->attach($bid->user_id, ['created_at' => now()]);
        return back()->with('success', 'Bid accepted successfully.');
    }

    public function declineBid(Request $request)
    {
        $request->validate([
            'bid_id' => 'required|exists:bids,id'
        ]);
        $bid = Bid::find($request->bid_id);
        $bid->delete();
        return back()->with('success', 'Bid declined successfully.');
    }
}
