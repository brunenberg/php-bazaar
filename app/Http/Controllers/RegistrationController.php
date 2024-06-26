<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function register(Request $request)
    {
        // Valideer de gegevens
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'user_type' => 'required|in:gebruiker,particuliere_verkoper,zakelijke_verkoper',
        ]);

        // Maak de gebruiker aan
        $user = new User();
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->user_type = $validatedData['user_type'];
        $user->language = 'nl';
        $user->save();

        // Als de gebruiker een zakelijke verkoper is, maak dan een bedrijf aan
        if ($validatedData['user_type'] === 'zakelijke_verkoper') {
            $company = new Company();
            $company->user_id = $user->id;
            $company->slug = $user->id . '-' . now()->timestamp;
            $company->save();
        }
        // Eventueel kun je de gebruiker hier inloggen en doorsturen naar een andere pagina
        Auth::login($user);

        // Stuur de gebruiker door naar een bedankpagina of een ander gewenst scherm
        return redirect('/')->with('success', __('messages.created_account'));
    }

    public function registerForm()
    {
        // Als de gebruiker al is ingelogd, stuur hem dan door naar de accountpagina
        if (Auth::check()) {
            return redirect('/account');
        }

        return view('auth.register');
    }

    

    // In je RegistrationController
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/account');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Valideer de gegevens
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Probeer de gebruiker in te loggen
        if (Auth::attempt($validatedData)) {
            // Als het inloggen lukt, stuur de gebruiker door naar de accountpagina
            return redirect('/account')->with('success', __('messages.logged_in'));
        } else {
            // Als het inloggen niet lukt, stuur de gebruiker terug naar de inlogpagina
            return redirect('/login')->withErrors(['email' => 'Deze inloggegevens komen niet overeen met onze gegevens.']);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', __('messages.logged_out'));
    }
}
