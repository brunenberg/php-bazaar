<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function addBid(Request $request)
    {
        $listing = Listing::find($request->listing_id);
        $user = auth()->user();

        if ($user && ($listing->user_id == $user->id || ($listing->company_id && $user->company && $listing->company_id == $user->company->id))) {
            return back()->with('error', __('messages.bid_on_own_listing'));
        }

        // Check if user has placed 3 bids on whole platform
        if (auth()->user()->bids->count() >= 4) {
            return back()->with('error', __('messages.max_bids'));
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

        return back()->with('success', __('messages.bid_placed'));
    }

    public function deleteBid(Request $request)
    {
        $request->validate([
            'bid_id' => 'required|exists:bids,id'
        ]);
        $bid = Bid::find($request->bid_id);
        $bid->delete();
        return back()->with('success', __('messages.bid_deleted'));
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
        $bid->listing->active = false;
        $bid->listing->save();
        return back()->with('success', __('messages.bid_accepted'));
    }

    public function declineBid(Request $request)
    {
        $request->validate([
            'bid_id' => 'required|exists:bids,id'
        ]);
        $bid = Bid::find($request->bid_id);
        $bid->accepted = false;
        $bid->save();
        return back()->with('success', __('messages.bid_denied'));
    }
}
