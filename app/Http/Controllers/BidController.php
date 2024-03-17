<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function addBid(Request $request)
    {
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
}
