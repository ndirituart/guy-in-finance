<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanRequestController; // Add this line
use App\Http\Controllers\AdminLoanController;   // Add this line

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// The three endpoints for login, logout and register
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

    // Since users SET 
    Route::post('/user/set-pin', [AuthController::class, 'setPin']); // Add this line
    // User-facing loan requests
    Route::post('/loan-requests', [LoanRequestController::class, 'submit']);
    Route::get('/loan-requests/status/{userId}', [LoanRequestController::class, 'status']);

    // Admin-facing loan approvals (consider adding authentication/authorization middleware here)
    Route::prefix('admin/loan-requests')->group(function () {
        Route::get('/pending', [AdminLoanController::class, 'pending']);
        Route::post('/{loanId}/approve', [AdminLoanController::class, 'approve']);
        Route::post('/{loanId}/deny', [AdminLoanController::class, 'deny']); // Corrected 'Route.post' to 'Route::post'
    });

});

