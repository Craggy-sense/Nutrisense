<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * REGISTER: This method handles creating new users.
     * It validates the input (name, email, password, age),
     * hashes the password for security, and saves the user to the database.
     */
    public function register(Request $request)
    {
        // 1. Validate that the user provided all required data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'age' => 'required|integer|min:1',
        ]);

        // 2. Create the user in the 'users' table in SQLite
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Securely scramble the password
            'age' => $validated['age'],
        ]);

        // 3. Return a success message
        return response()->json([
            'message' => 'User registered successfully',
            'userId' => $user->id,
        ], 201);
    }

    /**
     * LOGIN: This method verifies a user's credentials.
     * If correct, it generates a "Bearer Token" (Sanctum) which acts
     * like a digital key for the user to access protected data.
     */
    public function login(Request $request)
    {
        // 1. Validate login details
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Check if the user exists in our database
        $user = User::where('email', $request->email)->first();

        // 3. Verify the password matches the scrambled version in our DB
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // 4. Generate a unique security token for this session
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Send the token and user info back to the frontend
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'age' => $user->age,
            ]
        ]);
    }
}