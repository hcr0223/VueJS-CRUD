<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

Route::post('register', [UserController::class, 'store']);
Route::post('login', [LoginController::class, 'store']);

Route::middleware('auth:api')->group(function() {
    //Protected routes
    Route::post('users', [UserController::class, 'store']);
    Route::post('get-users', [UserController::class, 'all']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
});
