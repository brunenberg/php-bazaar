<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
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
        $user->save();

        // Als de gebruiker een zakelijke verkoper is, maak dan een bedrijf aan
        if ($validatedData['user_type'] === 'zakelijke_verkoper') {
            $company = new Company();
            $company->user_id = $user->id;
            $company->save();
        }

        // Eventueel kun je de gebruiker hier inloggen en doorsturen naar een andere pagina
        Auth::login($user);

        // Stuur de gebruiker door naar een bedankpagina of een ander gewenst scherm
        return redirect('/');
    }

    public function registerForm()
    {
        // Als de gebruiker al is ingelogd, stuur hem dan door naar de accountpagina
        if (Auth::check()) {
            return redirect('/account');
        }

        return view('auth.register');
    }

    public function account()
    {
        if (Auth::check()) {
            if (Auth::user()->user_type === 'zakelijke_verkoper') {
                $company = Company::where('user_id', Auth::user()->id)->first();
                return view('auth.account', [
                    'user' => Auth::user(),
                    'company' => $company
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

    public function login(Request $request)
    {
        // Check of de gebruiker al is ingelogd
        if (Auth::check()) {
            return redirect('/account');
        }

        // Valideer de gegevens
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Probeer de gebruiker in te loggen
        if (Auth::attempt($validatedData)) {
            // Als het inloggen lukt, stuur de gebruiker door naar de accountpagina
            return redirect('/account');
        } else {
            // Als het inloggen niet lukt, stuur de gebruiker terug naar de inlogpagina
            return redirect('/login');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
