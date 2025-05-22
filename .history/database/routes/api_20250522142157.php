<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// the three endpoints for login, logout and register
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

// The API routes for the application
Route::get('/', fn () => ['Laravel' => app()->version()]);


//Authentication passes through the api middleware for protection from unauthorized access
Route::middleware(['auth:sanctum'])->group(function () {
    // Your authenticated API routes here
    Route::get('/home', function () {
        return response()->json(['message' => 'Authenticated!']);
    });
});