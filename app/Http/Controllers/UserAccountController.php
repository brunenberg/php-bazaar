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
}
