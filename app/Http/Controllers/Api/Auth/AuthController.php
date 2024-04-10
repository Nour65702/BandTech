<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthController extends Controller
{
    private UserRepository $userRepository; // Declare the UserRepository property

    // Constructor to inject the UserRepository instance
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository; // Assign the UserRepository instance
    }

    // Register a new user
    public function register(RegisterRequest $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validated();

            // Create a new user using the UserRepository
            $user = $this->userRepository->create($validatedData);

            // Create a new API token for the user
            $token = $user->createToken('API_TOKEN')->plainTextToken;

            // Return a success response with the token
            return ApiResponse::success([
                'message' => 'User created successfully',
                'token' => $token
            ]);
        } catch (Throwable $th) {
            // Return an error response if an exception occurs
            return ApiResponse::error($th->getMessage(), 500);
        }
    }

    // Authenticate a user and generate a token
    public function login(LoginRequest $request)
    {
        try {
            // Validate the incoming request data
            $credentials = $request->validated();

            // Attempt to authenticate the user with provided credentials using Auth
            if (!Auth::attempt($credentials)) {
                // Return an error response if authentication fails
                return ApiResponse::error('Invalid credentials.', 401);
            }

            // Retrieve the authenticated user
            $user = Auth::user();

            // Update user's is_active status to true (assuming this is a necessary step after login)
            $user->update(['is_active' => true]);

            // Create a new API token for the user
            $token = $user->createToken('API_TOKEN')->plainTextToken;

            // Return a success response with the user data and token
            return ApiResponse::success([
                'message' => 'User logged in successfully',
                'user' => $user,
                'token' => $token
            ]);
        } catch (Throwable $th) {
            // Return an error response if an exception occurs
            return ApiResponse::error($th->getMessage(), 500);
        }
    }
}
