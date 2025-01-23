<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;  
use App\Http\Controllers\AuctionController;  
use App\Http\Controllers\CompanyAuthController;
use App\Http\Controllers\BidController;

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


// bids 
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bids', [BidController::class, 'store']);
    Route::get('/auctions/{auction}/bids', [BidController::class, 'index']);
});