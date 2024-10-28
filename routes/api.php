<?php

use App\Http\Controllers\cartController;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\owner;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\supplierOwner;
use App\Http\Controllers\userController;
use App\Http\Controllers\buyerController;
use App\Http\Controllers\supplierController;
use App\Http\Controllers\authenticationController;


Route::get('/users/{id}', [userController::class, 'show']);
Route::get('/users', [userController::class, 'index']);
Route::post('/login', [authenticationController::class, 'login']);
Route::post('/register', [userController::class, 'register']);
Route::get('/getSupplier',[supplierController::class, 'supplierindex']);



Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/logout', [authenticationController::class, 'logout']);
    Route::get('/me', [authenticationController::class, 'detectUser']);
    Route::get('/buyer/{id}',[buyerController::class, 'buyerIndex']);
    Route::post('/postprofile', [buyerController::class, 'postprofile']);

    //id buyer
    Route::patch('/updateprofile/{id}', [buyerController::class, 'updateprofile'])
    ->middleware('owner');

    //id buyer
    Route::delete('/deleteprofile/{id}', [buyerController::class, 'destroy'])
    ->middleware('owner');

    Route::post('/ulasan', [buyerController::class, 'ulasan']);

    //id ulasan
    Route::patch('/updateulasan/{id}', [buyerController::class, 'updateUlasan'])
    ->Middleware('ulasanOwner');

    //id ulasan
    Route::delete('/deleteulasan/{id}', [buyerController::class, 'deleteUlasan'])
    ->Middleware('ulasanOwner');

    Route::get('/getproduk',[supplierController::class, 'getproduk'])
    ->Middleware('supplierOwner');

    //postproduk
    Route::post('/postproduk', [supplierController::class, 'postproduk']);
    // ->Middleware(['atuh', 'supplierOwner']);

    //update
    Route::post('/updateproduk/{id}', [supplierController::class, 'updateProduk']);
    // ->Middleware(['atuh', 'supplierOwner']);

    Route::delete('/deleteproduk/{id}', [supplierController::class, 'deleteproduk']);
    // ->Middleware(['atuh', 'supplierOwner']);

    Route::post('addtocart', [cartController::class, 'postcart']);

    //update
    Route::post('/updatecart/{id}',[cartController::class, 'updatecart']);
    Route::post('/order', [cartController::class, 'postorder']);
});