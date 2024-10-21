<?php

use App\Http\Controllers\supplierController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\owner;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\buyerController;
use App\Http\Controllers\authenticationController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/users/{id}', [userController::class, 'show']);
Route::get('/users', [userController::class, 'index']);
Route::post('/login', [authenticationController::class, 'login']);
Route::post('/register', [userController::class, 'register']);
Route::get('/getSupplier',[supplierController::class, 'supplierindex']);
Route::get('/getproduk',[supplierController::class, 'getproduk']);


Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/logout', [authenticationController::class, 'logout']);
    Route::get('/me', [authenticationController::class, 'detectUser']);
    Route::get('/buyer/{id}',[buyerController::class, 'buyerIndex']);
    Route::post('/postprofile', [buyerController::class, 'postprofile']);

    //id buyer
    Route::patch('/updateprofile/{id}', [buyerController::class, 'updateprofile'])
    ->middleware('Owner');

    //id buyer
    Route::delete('/deleteprofile/{id}', [buyerController::class, 'destroy'])
    ->middleware('Owner');

    Route::post('/ulasan', [buyerController::class, 'ulasan']);

    //id ulasan
    Route::patch('/updateulasan/{id}', [buyerController::class, 'updateUlasan'])
    ->Middleware('ulasanOwner');

    //id ulasan
    Route::delete('/deleteulasan/{id}', [buyerController::class, 'deleteUlasan'])
    ->Middleware('ulasanOwner');

    //postproduk
    Route::post('/postproduk', [supplierController::class, 'postproduk']);

    //produk id
    Route::patch('/updateproduk/{id}', [supplierController::class, 'updateProduk']);

    Route::delete('/deleteproduk/{id}', [supplierController::class, 'deleteproduk']);
});