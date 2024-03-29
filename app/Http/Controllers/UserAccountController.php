<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAccountController extends Controller
{
    public function account()
    {
        if (Auth::check()) {
            $user = User::find(Auth::user()->id);
            $favorites = $user->favorites()->get();
            if (Auth::user()->user_type === 'zakelijke_verkoper') {
                $company = Company::where('user_id', Auth::user()->id)->first();
                $templates = Template::all();
                $activeTemplates = $company->templates()->get();
                $bids = $company->listings->map(function ($listing) {
                    return $listing->bids->where('accepted', null);
                })->flatten();
                return view('auth.account', [
                    'user' => Auth::user(),
                    'company' => $company,
                    'templates' => $templates,
                    'activeTemplates' => $activeTemplates,
                    'favorites' => $favorites,
                    'bids' => $bids

                ]);
            } else if (Auth::user()->user_type === 'gebruiker') {
                // Get purchases from pivot table
                $purchases = $user->bought()->get();

                return view('auth.account', [
                    'user' => Auth::user(),
                    'favorites' => $favorites,
                    'purchases' => $purchases
                ]);
            } else if (Auth::user()->user_type === 'particuliere_verkoper'){
                $bids = Auth::user()->listings->map(function ($listing) {
                    return $listing->bids->where('accepted', null);
                })->flatten();
                return view('auth.account', [
                    'user' => Auth::user(),
                    'favorites' => $favorites,
                    'bids' => $bids
                ]);
            } else {
                return view('auth.account', [
                    'user' => Auth::user(),
                    'favorites' => $favorites
                ]);
            }
        } else {
            return view('auth.login');
        }
    }

    public function removeFavorite(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $listingId = $request->input('listing_id');
        $favorite = $user->favorites()->wherePivot('listing_id', $listingId)->first();
        if ($favorite) {
            $user->favorites()->detach($favorite->id);
        }
        return redirect()->back()->with('success', 'Listing removed from favourites.');
    }

    public function addFavorite(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $listingId = $request->input('listing_id');
        if (!$user->favorites()->where('listing_id', $listingId)->exists()) {
        $user->favorites()->attach($listingId);
        }
        return redirect()->back()->with('success', 'Listing added to favourites.');
    }

}
