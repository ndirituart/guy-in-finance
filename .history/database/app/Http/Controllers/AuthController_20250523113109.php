<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showregister() {
        return view('register'); // Assuming you still have a Blade register form
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'first_name' => 'required',
            'middle_name' => 'nullable', // optional field
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'birthday' => 'required|date|before:' . now()->subYears(14)->format('Y-m-d'), //users ONLY over 14 years
            'id_number' => 'nullable|unique:users',
            'phone_number' => 'required|unique:users|regex:/^\+254\d{9}$/',
            'password' => 'required|min:6|confirmed', // 'confirmed' expects a 'password_confirmation' field
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'birthday' => $request->birthday,
            'id_number' => $request->id_number,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);
        // Generate a unique token for the user OTP
        $token = $user->createToken('main')->plainTextToken;

        return response()->json(['message' => 'Account created successfully!', 'user' => $user, 'token' => $token], 201);
    }

    public function showLogin() {
        return view('login'); // Assuming you still have a Blade login form
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        //OTP verification if needed
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('main')->plainTextToken;
            $request->session()->regenerate();
            return response()->json(['redirect' => '/home', 'token' => $token], 200); // Return token on successful login
        }

        return response()->json(['errors' => ['email' => 'Invalid credentials.']], 401);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['redirect' => '/login'], 200);
    }
}