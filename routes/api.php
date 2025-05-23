<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthMController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'middleware' => 'api',
    'prefix' => '/vendor'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

Route::group([
    'middleware' => 'api',
    'prefix' => '/member'
], function ($router) {
    Route::post('/register', [AuthMController::class, 'register'])->name('register');
    Route::post('/login', [AuthMController::class, 'login'])->name('login');
    Route::post('/logout', [AuthMController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthMController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthMController::class, 'me'])->middleware('auth:api')->name('me');
});
