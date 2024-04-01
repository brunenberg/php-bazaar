<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller {
    public function setLocale(Request $request) {
        if (Auth::check()) {
            $user = Auth::user();
            $user->language = $request->locale;
            $user->save();
        } else {
            Session::put('locale', $request->locale);
        }

        $language = '';
        switch ($request->locale) {
            case 'en':
                $language = 'English';
                break;
            case 'nl':
                $language = 'Nederlands';
                break;
        }

        return back()->with('success', __('messages.language_changed') . $language . '.');
    }
}
