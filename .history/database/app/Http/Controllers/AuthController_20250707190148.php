<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ... (existing register, login, logout, setPin methods) ...

    /**
     * Get the authenticated user's profile data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getProfile(Request $request)
    {
        // Auth::user() automatically returns the authenticated user based on the token
        return response()->json(['user' => $request->user()]);
    }

    /**
     * Update the authenticated user's profile data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->User_id . ',User_id', // Exclude current user's ID
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->User_id . ',User_id', // Exclude current user's ID
            'birthday' => 'required|date',
            'phone_number' => 'required|string|max:255',
            // Do NOT include password or PIN here for direct update; handle separately
        ]);

        $user->update([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'birthday' => $request->birthday,
            'phone_number' => $request->phone_number,
        ]);

        return response()->json(['message' => 'Profile updated successfully!', 'user' => $user]);
    }
}