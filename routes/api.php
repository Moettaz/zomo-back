<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TransporteurController;
use App\Http\Controllers\TrajetController;
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

Route::middleware('api')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    
    // Client Routes
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/clients/{id}', [ClientController::class, 'show']);
    Route::put('/clients/{id}', [ClientController::class, 'update']);
    Route::delete('/clients/{id}', [ClientController::class, 'destroy']);

    // Transporteur Routes
    Route::get('/transporteurs', [TransporteurController::class, 'index']);
    Route::post('/transporteurs', [TransporteurController::class, 'store']);
    Route::get('/transporteurs/{id}', [TransporteurController::class, 'show']);
    Route::put('/transporteurs/{id}', [TransporteurController::class, 'update']);
    Route::delete('/transporteurs/{id}', [TransporteurController::class, 'destroy']);

    // Trajet Routes
    Route::get('/trajets', [TrajetController::class, 'index']);
    Route::post('/trajets', [TrajetController::class, 'store']);
    Route::get('/trajets/{id}', [TrajetController::class, 'show']);
    Route::put('/trajets/{id}', [TrajetController::class, 'update']);
    Route::delete('/trajets/{id}', [TrajetController::class, 'destroy']);
    Route::get('/trajets/transporteur/{transporteur_id}', [TrajetController::class, 'getTrajetsByTransporteur']);
    Route::get('/trajets/client/{client_id}', [TrajetController::class, 'getTrajetsByClient']);
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
