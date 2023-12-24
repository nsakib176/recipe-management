<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;


class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        // Validate user data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Create user in database
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            // Generate JWT token
            $token = $user->createToken('auth_token')->plainTextToken;

            // Return a success response with token
            return response()->json([
                'message' => 'User registered successfully.',
                'user' => $user,
                'token' => $token,
            ], 201);
        
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed.',
                'error' => $e->getMessage(),
            ], 400);
        }
        
    }

    // Authenticate user
    public function login(Request $request)
    {
        // validate the data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        // check $credentials
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Generate JWT token
            /** @var \App\Models\User $user **/
            $token = $user->createToken('auth_token')->plainTextToken;

            // Return a success response with token
            return response()->json([
                'message' => 'User logged in successfully.',
                'user' => $user,
                'token' => $token,
            ]);
        }else{
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        
        }
    }

    // Logout user (invalidate token)
    public function logout()
    {
        try {
            // Revoke all tokens for the user
            $tokens = Auth::user()->tokens;
            foreach ($tokens as $token) {
                $token->revoke();
            }

            return response()->json([
                'message' => 'User successfully logged out.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to logout.',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
