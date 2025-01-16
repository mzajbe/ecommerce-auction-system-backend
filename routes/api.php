<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;  
use App\Http\Controllers\AuctionController;  

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// New product routes  
Route::get('/products', [ProductController::class, 'index']);  
Route::post('/products', [ProductController::class, 'store']);

// authentication 
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'userInfo']);



//auction

Route::get('/auctions', [AuctionController::class, 'index']);
Route::post('/auctions', [AuctionController::class, 'store']);

