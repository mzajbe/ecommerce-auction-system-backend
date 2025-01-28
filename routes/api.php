<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;  
use App\Http\Controllers\AuctionController;  
use App\Http\Controllers\CompanyAuthController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SavedAuctionController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// authentication 
//user and admin
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'userInfo']);

//company
Route::post('/company/register', [CompanyAuthController::class, 'register']);
Route::post('/company/login', [CompanyAuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/company', [CompanyAuthController::class, 'companyInfo']);




//auction

Route::get('/auctions', [AuctionController::class, 'index']);
Route::post('/auctions', [AuctionController::class, 'store']);
Route::get('/auctions/search', [AuctionController::class, 'search']); // New search route
// Get all live auctions
Route::get('/auctions/live', [AuctionController::class, 'getLiveAuctions']);

// Get all auctions of a specific company
Route::get('/companies/{companyId}/auctions', [AuctionController::class, 'getAuctionsByCompany']);

// Get currently live auctions of a specific company
Route::get('/companies/{companyId}/auctions/live', [AuctionController::class, 'getLiveAuctionsByCompany']);

// Add these routes to your existing routes
Route::put('/auctions/{id}', [AuctionController::class, 'update']);
Route::delete('/auctions/{id}', [AuctionController::class, 'destroy']);


// Save auction
// Save auction with explicit user_id
Route::post('/saved-auctions', [SavedAuctionController::class, 'save']);

// Get saved auctions for a user
Route::get('/saved-auctions', [SavedAuctionController::class, 'index']);

// Remove saved auction with explicit user_id
Route::delete('/saved-auctions', [SavedAuctionController::class, 'remove']);



// bids 
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bids', [BidController::class, 'store']);
    Route::get('/auctions/{auction}/bids', [BidController::class, 'index']);
});


//cart

Route::middleware('auth:sanctum')->group(function () {
    // Add winning auction to cart
    Route::post('/cart/add-winning-auction/{auctionId}', [CartController::class, 'addWinningAuctionToCart']);

    // View user's cart
    Route::get('/cart', [CartController::class, 'viewCart']);
});