<?php

use App\Models\Listing;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RegistrationController;
use App\Models\Company;

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

Route::get('/pdf', [PdfController::class, 'generate'])->name('pdf');

Route::post('/update-info', [CompanyController::class, 'updateInfo'])->name('update-info');

// Deze route moet onderaan
Route::get('/company/{slug}', [CompanyController::class, 'show'])->name('page.show');