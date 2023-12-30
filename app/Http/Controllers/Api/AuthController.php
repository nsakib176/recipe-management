<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;


class AuthController extends Controller
{
    // Swagger code
    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Authentication"},
     *     summary="Register a new user for recipe management",
     *     description="Creates a new user account to access and manage recipes",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             ref="#/components/schemas/UserRegistrationRequest"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/UserResponse"
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 "errors": {
     *                     type="object",
     *                     additionalProperties={
     *                         type="array",
     *                         items={
     *                             type="string"
     *                         }
     *                     }
     *                 }
     *             }
     *         )
     *     )
     * )
     */
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

    // Swagger code
    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Authentication"},
     *     summary="Authenticate a user and obtain an access token",
     *     description="Logs in a user with the provided credentials and returns an access token for subsequent requests",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(ref="#/components/schemas/UserResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
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

    // Swagger code
    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Authentication"},
     *     summary="Invalidate the current user's access token",
     *     description="Logs out the user by invalidating their access token, preventing further authenticated requests",
     *     security={{"bearerAuth": {}}},  // Require authentication for logout
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
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
