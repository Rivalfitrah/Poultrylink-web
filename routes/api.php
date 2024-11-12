<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\owner;
use App\Http\Middleware\CheckSupplierVerified;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\supplierOwner;
use App\Http\Middleware\penjual;
use App\Http\Controllers\cartController;
use App\Http\Controllers\fileController;
use App\Http\Controllers\userController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\buyerController;
use App\Http\Controllers\verifController;
use App\Http\Controllers\supplierController;
use App\Http\Controllers\authenticationController;


Route::get('/users/{id}', [userController::class, 'show']);
Route::get('/users', [userController::class, 'index']);

Route::post('/login', [authenticationController::class, 'login']);
Route::post('/register', [userController::class, 'register']);
Route::post('/loginadmin', [adminController::class, 'login']); 


//verifsupp
Route::post('/verifysupplier/{id}', [adminController::class, 'verifysupplier']);

Route::get('/getSupplier',[supplierController::class, 'supplierindex']);
Route::get('/kategori', [supplierController::class, 'kategori']);
Route::get('/produkall', [supplierController::class, 'getproduk']);

Route::post('/upload-dummy', [fileController::class, 'uploadDummyFile']);
Route::post('/upload', [fileController::class, 'upload']);
Route::post('/update-image', [fileController::class, 'updateImage']);
Route::post('/delete-image', [fileController::class, 'deleteImage']);
Route::post('/postprodukp', [supplierController::class, 'postproduk']);
Route::post('/upload-multiple-files', [supplierController::class, 'uploadMultipleFiles']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/logout', [authenticationController::class, 'logout']);
    Route::get('/me', [authenticationController::class, 'detectUser']);
    Route::get('/buyer/{id}',[buyerController::class, 'buyerIndex']);

    //post profile
    Route::post('/postprofile', [buyerController::class, 'postprofile']);
    //updateprofile
    Route::post('/updateprofile', [buyerController::class, 'updateprofile']);
    //getdetailprofile
    Route::get('/getprofile', [buyerController::class, 'getprofile']);
    //postulasan
    Route::post('/ulasan', [buyerController::class, 'ulasan']);
    //id ulasan
    Route::patch('/updateulasan/{id}', [buyerController::class, 'updateUlasan']);
    //id ulasan
    Route::delete('/deleteulasan/{id}', [buyerController::class, 'deleteUlasan']);

    //login supplier
    Route::post('/loginsupplier', [AuthenticationController::class, 'loginSupplier'])->middleware('Penjual');
    //getproduksupplier
    Route::get('/produksupplier', [supplierController::class, 'getProdukSupplier'])->middleware('Penjual');
    //get order 
    Route::get('/supplier/orders', [supplierController::class, 'getorder'])->middleware('Penjual');
    //check supplier
    Route::get('/checkSupplier', [supplierController::class, 'checkSupplier'])->middleware('Penjual');
    //post produk
    Route::post('/postproduk', [supplierController::class, 'postproduk'])->middleware('Penjual');
    //update produk
    Route::post('/updateproduk/{id}', [supplierController::class, 'updateproduk'])->middleware('Penjual');
    //delete produkx
    Route::delete('/deleteproduk/{id}', [supplierController::class, 'deleteproduk'])->middleware('Penjual');

    //postverif
    Route::post('/postverif', [verifController::class, 'postverif']);

    //postcart
    Route::post('/addtocart', [cartController::class, 'postcart']);
    //updatecart
    Route::post('/updatecart/{id}',[cartController::class, 'updatecart']);
    //postorder
    Route::post('/order', [cartController::class, 'postorder']);



});
