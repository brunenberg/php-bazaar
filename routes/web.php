<?php

use App\Models\Company;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserAccountController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [RegistrationController::class, 'showLoginForm'])->name('login');
Route::post('/login', [RegistrationController::class, 'login']);
Route::get('/account', [UserAccountController::class, 'account']);
Route::get('/register', [RegistrationController::class, 'registerForm']);
Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/logout', [RegistrationController::class, 'logout'])->name('logout');

Route::get('/pdf', [PdfController::class, 'generate'])->name('pdf');

Route::post('/update-info', [CompanyController::class, 'updateInfo'])->name('update-info');

Route::get('/create-listing', [ListingController::class, 'create'])->name('create-listing-form')->middleware('checkUserType');
Route::post('/create-listing', [ListingController::class, 'store'])->name('create-listing')->middleware('checkUserType');
Route::get('/listings', [ListingController::class, 'index'])->name('listings')->middleware('checkUserType');
Route::get('/edit-listing/{id}', [ListingController::class, 'edit'])->name('edit-listing')->middleware('checkListingOwner');
Route::put('/update-listing/{id}', [ListingController::class, 'update'])->name('update-listing')->middleware('checkListingOwner');
Route::delete('/delete-listing/{id}', [ListingController::class, 'destroy'])->name('delete-listing')->middleware('checkListingOwner');

Route::post('/add-template', [CompanyController::class, 'addTemplate'])->name('add-template');
Route::post('/remove-template', [CompanyController::class, 'removeTemplate'])->name('remove-template');
Route::post('/templates/order-up', [CompanyController::class, 'orderUp'])->name('templates.orderUp');
Route::post('/templates/order-down', [CompanyController::class, 'orderDown'])->name('templates.orderDown');

Route::post('/account/remove-favorite', [UserAccountController::class, 'removeFavorite'])->name('account/remove-favorite');
Route::post('/account/add-favorite', [UserAccountController::class, 'addFavorite'])->name('account/add-favorite');

Route::get('/listing/{id}', [ListingController::class, 'show'])->name('listing.show');

Route::post('/setlocale', [LocaleController::class, 'setLocale'])->name('setlocale');

Route::get('/get_personal_access_token', function () {
    $user = auth()->user(); /** @var User $user */
    return $user->createToken('token')->plainTextToken; 
})->middleware('auth');
Route::post('/company/review', [CompanyController::class, 'addReview'])->name('company/review');
Route::post('/listing/review', [ListingController::class, 'addReview'])->name('listing/review');
Route::post('/listing/delete-review', [ListingController::class, 'deleteReview'])->name('listing/delete-review');
Route::post('/company/delete-review', [CompanyController::class, 'deleteReview'])->name('company/delete-review');


// Deze route moet onderaan
Route::get('/company/{slug}', [CompanyController::class, 'show'])->name('page.show');
