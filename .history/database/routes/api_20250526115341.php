<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AuthController;

// the three endpoints for login, logout and register
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);



// The API routes for the application which is the default route
// Route::get('/', fn () => ['Laravel' => app()->version()]);


//Authentication passes through the api middleware for protection from unauthorized access
Route::middleware(['auth:sanctum'])->group(function () {
    // Your authenticated API routes here
    Route::get('/home', function () {
        return response()->json(['message' => 'Authenticated!']);
    });
//For Laravel logs and to get errors

    Route::get('/test-log', function () {
    \Log::info('Test log route hit');
    return response()->json(['message' => 'It works']);
});

});