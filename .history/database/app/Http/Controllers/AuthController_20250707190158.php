<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'birthday' => 'required|date',
            'id_number' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'birthday' => $request->birthday,
            'id_number' => $request->id_number,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password), // Ensure password is hashed
            'loan_balance' => 0.00, // Initialize loan balance
        ]);

        // Log the user in immediately after registration
        Auth::login($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully!',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Revoke old tokens and create a new one
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully!',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully!']);
    }

    /**
     * Set or update the user's PIN.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setPin(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,User_id', // Validate userId exists in users table
            'pin' => 'required|string|digits:4', // Ensure PIN is 4 digits
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Security check: Ensure the authenticated user matches the userId provided
        // This prevents one user from setting another user's PIN
        if ($user->User_id != $request->userId) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        // Update the user's PIN
        $user->PIN = Hash::make($request->pin); // Hash the PIN before saving
        $user->save();

        return response()->json(['message' => 'PIN updated successfully']);
    }
}