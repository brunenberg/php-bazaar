<?php

use App\Models\Company;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BidController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AgendaController;
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

// Routes for all users (no middleware)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [RegistrationController::class, 'showLoginForm'])->name('login');
Route::post('/login', [RegistrationController::class, 'login']);
Route::get('/account', [UserAccountController::class, 'account']);
Route::get('/register', [RegistrationController::class, 'registerForm']);
Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/logout', [RegistrationController::class, 'logout'])->name('logout');
Route::get('/listing/{id}', [ListingController::class, 'show'])->name('listing.show');
Route::post('/setlocale', [LocaleController::class, 'setLocale'])->name('setLocale');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Middleware group for users with company
Route::middleware(['company'])->group(function () {
    Route::post('/update-info', [CompanyController::class, 'updateInfo'])->name('update-info');
    Route::post('/add-template', [CompanyController::class, 'addTemplate'])->name('add-template');
    Route::post('/remove-template', [CompanyController::class, 'removeTemplate'])->name('remove-template');
    Route::post('/templates/order-up', [CompanyController::class, 'orderUp'])->name('templates.orderUp');
    Route::post('/templates/order-down', [CompanyController::class, 'orderDown'])->name('templates.orderDown');
    Route::post('/contract/accept', [CompanyController::class, 'acceptContract'])->name('contract/accept');
    Route::post('/contract/reject', [CompanyController::class, 'rejectContract'])->name('contract/reject');
    Route::get('/get_personal_access_token', function () {
        $user = auth()->user(); /** @var User $user */
        return $user->createToken('token')->plainTextToken; 
    })->middleware('auth');
});

// Middleware group for routes where user has to be signed in
Route::middleware(['auth'])->group(function () {
    Route::post('/company/review', [CompanyController::class, 'addReview'])->name('company/review');
    Route::post('/listing/review', [ListingController::class, 'addReview'])->name('listing/review');
    Route::post('/account/remove-favorite', [UserAccountController::class, 'removeFavorite'])->name('account/remove-favorite');
    Route::post('/account/add-favorite', [UserAccountController::class, 'addFavorite'])->name('account/add-favorite');
    Route::post('/listing/delete-review', [ListingController::class, 'deleteReview'])->name('listing/delete-review');
    Route::post('/company/delete-review', [CompanyController::class, 'deleteReview'])->name('company/delete-review');
    Route::post('/listing/bid', [BidController::class, 'addBid'])->name('listing/bid');
    Route::post('/listing/delete-bid', [BidController::class, 'deleteBid'])->name('listing/delete-bid');
    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda');
    Route::post('/agenda/return', [ListingController::class, 'return'])->name('return');
});

// Middleware group for users that need to have a seller account
Route::middleware(['checkUserType'])->group(function () {
    Route::get('/create-listing', [ListingController::class, 'create'])->name('create-listing-form');
    Route::post('/create-listing', [ListingController::class, 'store'])->name('create-listing');
    Route::get('/listings', [ListingController::class, 'index'])->name('listings');
    Route::post('/listing/accept-bid', [BidController::class, 'acceptBid'])->name('listing/accept-bid');
    Route::post('/listing/decline-bid', [BidController::class, 'declineBid'])->name('listing/decline-bid');
    Route::post('/upload-csv', [ListingController::class, 'uploadCsv'])->name('upload-csv');
});

// Middleware group for users that need to be owner of listing
Route::middleware(['checkListingOwner'])->group(function () {
    Route::get('/edit-listing/{id}', [ListingController::class, 'edit'])->name('edit-listing');
    Route::put('/update-listing/{id}', [ListingController::class, 'update'])->name('update-listing');
    Route::delete('/delete-listing/{id}', [ListingController::class, 'destroy'])->name('delete-listing');
    Route::put('/activate-listing/{id}', [ListingController::class, 'activate'])->name('activate-listing');
    Route::put('/deactivate-listing/{id}', [ListingController::class, 'deactivate'])->name('deactivate-listing');
    Route::post('/listing/{id}/upload-image', [ListingController::class, 'uploadImage'])->name('upload-listing-image');
});

// Middleware group for users that need to be admin
Route::middleware(['admin'])->group(function () {
    Route::get('/companies', [CompanyController::class, 'allCompanies'])->name('companies');
    Route::get('/companies', [CompanyController::class, 'allCompanies'])->name('companies');
    Route::post('/company/download-contract', [CompanyController::class, 'downloadContract'])->name('company/download-contract');
    Route::post('/company/upload-contract', [CompanyController::class, 'uploadContract'])->name('company/upload-contract');
});

// Middleware group for users normal users (logged in)
Route::middleware(['normalUser'])->group(function () {
    Route::get('/cart', [CartController::class, 'cart'])->name('cart');
    Route::post('/listing/add-to-cart', [CartController::class, 'addToCart'])->name('listing/add-to-cart');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart/remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart/checkout');
}); 

// This route has to be last
Route::get('/company/{slug}', [CompanyController::class, 'show'])->name('page.show');
