<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication Routes
Route::post('/register', [App\Http\Controllers\UserController::class, 'register']);
Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
// Client Routes
Route::get('/clients', [App\Http\Controllers\ClientController::class, 'index']);
Route::get('/clients/{id}', [App\Http\Controllers\ClientController::class, 'show']);
Route::put('/clients/{id}', [App\Http\Controllers\ClientController::class, 'update']);
Route::delete('/clients/{id}', [App\Http\Controllers\ClientController::class, 'destroy']);
