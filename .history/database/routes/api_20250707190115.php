<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanRequestController;
use App\Http\Controllers\AdminLoanController;

// These routes DO NOT require authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// These routes REQUIRE authentication
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // User-facing API routes
    Route::get('/home', function () {
        return response()->json(['message' => 'Authenticated!']);
    });
    Route::get('/test-log', function () {
        \Log::info('Test log route hit');
        return response()->json(['message' => 'It works']);
    });

    // New route to get authenticated user's profile
    Route::get('/user/profile', [AuthController::class, 'getProfile']); // Add this line
    // New route to update authenticated user's profile
    Route::put('/user/profile', [AuthController::class, 'updateProfile']); // Add this line (using PUT for updates)

    // New route for setting PIN (already added in previous step)
    Route::post('/user/set-pin', [AuthController::class, 'setPin']);

    // Loan-related routes
    Route::post('/loan-requests', [LoanRequestController::class, 'submit']);
    Route::get('/loan-requests/status/{userId}', [LoanRequestController::class, 'status']);

    // Admin-facing loan approvals
    Route::prefix('admin/loan-requests')->group(function () {
        Route::get('/pending', [AdminLoanController::class, 'pending']);
        Route::post('/{loanId}/approve', [AdminLoanController::class, 'approve']);
        Route::post('/{loanId}/deny', [AdminLoanController::class, 'deny']);
    });
});