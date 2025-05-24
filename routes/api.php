<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TransporteurController;
use App\Http\Controllers\TrajetController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CallHistoryController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ReclamationController;
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
    Route::get('/profile/{userId}', [UserController::class, 'getProfile']);

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

    // Reservation Routes
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
    Route::get('/reservations/transporteur/{transporteur_id}', [ReservationController::class, 'getReservationsByTransporteur']);
    Route::get('/reservations/client/{client_id}', [ReservationController::class, 'getReservationsByClient']);

    // Call History Routes
    Route::post('/call-history', [CallHistoryController::class, 'store']);
    Route::get('/call-history/{id}', [CallHistoryController::class, 'getById']);

    // Notification Routes
    Route::post('/notifications', [NotificationsController::class, 'store']);

    // Evaluation Routes
    Route::post('/evaluations', [EvaluationController::class, 'store']);
    Route::get('/evaluations/client/{clientId}', [EvaluationController::class, 'getByClientId']);
    Route::get('/evaluations/transporteur/{transporteurId}', [EvaluationController::class, 'getByTransporteurId']);

    // Reclamation Routes
    Route::get('/reclamations', [ReclamationController::class, 'index']);
    Route::post('/reclamations', [ReclamationController::class, 'store']);
    Route::get('/reclamations/{reclamation}', [ReclamationController::class, 'show']);
    Route::put('/reclamations/{reclamation}', [ReclamationController::class, 'update']);
    Route::delete('/reclamations/{reclamation}', [ReclamationController::class, 'destroy']);
    Route::get('/reclamations/client/{client_id}', [ReclamationController::class, 'getByClientId']);
    Route::get('/reclamations/transporteur/{transporteur_id}', [ReclamationController::class, 'getByTransporteurId']);

    // Update Device Token
    Route::post('/update-device-token', [UserController::class, 'updateDeviceToken']);
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
