<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function cart()
    {
        
        $cartItems = session()->get('cart', []);
        return view('cart', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        // Check if the listing is previously bought
        $bought = auth()->user()->bought()->where('listing_id', $request->listing_id)->exists();
        if ($bought) {
            return redirect()->back()->with('error', __('messages.already_bought'));
        }
        // Check if already in shopping cart
        $cart = session()->get('cart', []);
        if (in_array($request->listing_id, array_column($cart, 'id'))) {
            return redirect()->back()->with('error', __('messages.already_in_cart'));
        }

        $listing = Listing::find($request->listing_id);
        $cart = session()->get('cart', []);
        $cart[] = $listing;
        session()->put('cart', $cart);
        return redirect()->back()->with('success', __('messages.listing_added_to_cart'));
    }

    public function remove(Request $request)
    {
        $listing = json_decode($request->input('item'), true);
        $listingId = $listing['id'];

        $cart = session()->get('cart', []);

        $index = array_search($listingId, array_column($cart, 'id'));

        if ($index !== false) {
            unset($cart[$index]);
            $cart = array_values($cart);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', __('messages.listing_removed_from_cart'));
    }


    public function checkout()
    {
        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            return redirect()->back()->with('error', __('messages.cart_empty'));
        }
        
        // Add the listings to the bought table
        $cartItems = json_decode(json_encode($cartItems), true);
        foreach ($cartItems as $item) {
            auth()->user()->bought()->attach($item['id']);
            // Set the listing to inactive
            Listing::where('id', $item['id'])->update(['active' => false]);
        }

        session()->forget('cart');
        return redirect()->back()->with('success', __('messages.checked_out'));
    }
}
