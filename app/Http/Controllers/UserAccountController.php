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
            if (Auth::user()->user_type === 'zakelijke_verkoper') {
                $company = Company::where('user_id', Auth::user()->id)->first();
                $templates = Template::all();
                $activeTemplates = $company->templates()->get();
                return view('auth.account', [
                    'user' => Auth::user(),
                    'company' => $company,
                    'templates' => $templates,
                    'activeTemplates' => $activeTemplates

                ]);
            } else if (Auth::user()->user_type === 'gebruiker') {
                // Get favorites from pivot table between user and listing called user_favorites
                $user = User::find(Auth::user()->id);
                $favorites = $user->favorites()->get();
                
                return view('auth.account', [
                    'user' => Auth::user(),
                    'favorites' => $favorites
                ]);
            } else {
                return view('auth.account', [
                    'user' => Auth::user()
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
        return redirect()->back();
    }

    public function addFavorite(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $listingId = $request->input('listing_id');
        if (!$user->favorites()->where('listing_id', $listingId)->exists()) {
        $user->favorites()->attach($listingId);
        }
        return redirect()->back();
    }

}
