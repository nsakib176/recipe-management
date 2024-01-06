<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
/**
 * @OA\Post(
 *     path="/api/register",
 *     operationId="registerUser",
 *     tags={"Authentication"},
 *     summary="Register a new user",
 *     description="Create a new user account with name, email, and password",
 *     @OA\RequestBody(
 *         required=true,
 *         description="User information for registration",
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name", "email", "password"},
 *             properties={
 *                 @OA\Property(property="name", type="string", example="John Doe", description="User's full name"),
 *                 @OA\Property(property="email", type="string", format="email", example="john.doe@example.com", description="User's email address"),
 *                 @OA\Property(property="password", type="string", format="password", example="password123", description="User's password"),
 *             },
 *         ),
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User registered successfully",
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error or user already exists",
 *     ),
 * )
 */
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
        } else {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }
    }


    // Logout user (invalidate token)
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'message' => 'User successfully logged out.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Logout failed.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
