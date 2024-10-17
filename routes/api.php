<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\buyerController;
use App\Http\Controllers\authenticationController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/users', [userController::class, 'index'])->middleware(['auth:sanctum']);
Route::get('/users/{id}', [userController::class, 'show'])->middleware(['auth:sanctum']);
Route::get('/buyer/{id}',[buyerController::class, 'buyerIndex'])->middleware(['auth:sanctum']);
Route::post('/login', [authenticationController::class, 'login']);
Route::get('/logout', [authenticationController::class, 'logout'])->middleware(['auth:sanctum']);
Route::get('/me', [authenticationController::class, 'detectUser'])->middleware(['auth:sanctum']);