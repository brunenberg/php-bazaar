<?php

use App\Models\Listing;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home', [
        'listings' => Listing::all()
    ]);
});

Route::get('/listing/{listing}', function ($id) {
    return view('listing', [
        'listing' => Listing::find($id)
    ]);
});


Route::get('/listing', function () {
    return view('listing');
});

Route::post('/login', [RegistrationController::class, 'login'])->name('login');
Route::get('/account', [RegistrationController::class, 'account']);
Route::get('/login', [RegistrationController::class, 'login']);
Route::get('/register', [RegistrationController::class, 'registerForm']);
Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/logout', [RegistrationController::class, 'logout'])->name('logout');
Route::post('/setlocale', 'LocaleController@setLocale')->name('setlocale');