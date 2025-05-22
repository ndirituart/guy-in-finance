<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
// the three endpoints for login, logout and register
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Your authenticated API routes here
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Authenticated!']);
    });
});