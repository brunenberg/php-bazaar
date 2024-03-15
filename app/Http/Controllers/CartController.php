<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Cart;

class CartController extends Controller
{
    private Cart $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function cart()
    {
        return view('cart', [
            'cart' => $this->cart->content(),
        ]);
    }

    // Add to cart fuction using mindscmd cart package
    public function addToCart(Request $request)
    {
        $validatedData = $request->validate([
            'listing_id' => 'required|numeric',
        ]);
        $listing = Listing::find($validatedData['listing_id']);

        $this->cart->add($listing->id, $listing->title, 1, 1);
        return redirect()->back();
    }

    public function checkIfBought($listingId)
    {
        $bought = auth()->user()->bought()->where('listing_id', $listingId)->exists();
        return $bought;
    }

    public function remove(Request $request)
    {
        $validatedData = $request->validate([
            'row_id' => 'required|string',
        ]);
        $this->cart->remove($validatedData['row_id']);
        return redirect()->back();
    }

    public function checkout()
    {
        // Add items in cart to user's bought table in database (user_bought)
        foreach ($this->cart->content() as $item) {
            auth()->user()->bought()->attach($item->id, ['created_at' => now(), 'updated_at' => now()]);
        }

        $this->cart->destroy();
        return redirect()->back();
    }
}
