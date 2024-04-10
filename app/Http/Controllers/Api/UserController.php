<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\Users\UserResource;
use App\Repositories\UserRepository;


class UserController extends Controller
{

    private UserRepository $userRepo; // Instance of UserRepository for accessing user data

    // Constructor to inject UserRepository instance
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo; // Assign UserRepository instance to $userRepo property
    }

    public function index()
    {
        // Get all users from UserRepository
        $users = $this->userRepo->all();

        // Loop through each user
        foreach ($users as $user) {
            // Get the avatar URL for the user's media (if any)
            $user->avatar_url = optional($user->media->first())->original_url;
            // Remove the 'media' property from the user object to avoid unnecessary data in response
            unset($user->media);
        }
        // Return a success response with the list of users and their avatar URLs
        return ApiResponse::success([
            'users' => UserResource::collection($users)
        ]);
    }



    // Retrieve a single user by ID
    public function show($id)
    {
        // Try to retrieve the user by ID
        try {
            // Find the user by ID using UserRepository
            $user = $this->userRepo->findOrFail($id);

            // Get the avatar URL for the user's media (if any)
            $avatarUrl = optional($user->media->first())->original_url;
            // Remove the 'media' property from the user object to avoid unnecessary data in response
            unset($user->media);

            // Return a success response with the user data and their avatar URL
            return ApiResponse::success(['user' => array_merge($user->toArray(), ['avatar_url' => $avatarUrl])]);
        } catch (\Throwable $th) {
            // Return an error response if an exception occurs
            return ApiResponse::error($th->getMessage(), 500);
        }
    }


    // Store a new user
    public function store(StoreUserRequest $request)
    {
        // Try to store the new user
        try {
            // Validate the incoming request data
            $userData = $request->validated();

            // If an avatar is uploaded, store it and get its URL
            if ($request->hasFile('avatar')) {
                // Create the user using UserRepository
                $user = $this->userRepo->create($userData);
                // Add the avatar image from request and store it in 'images' media collection
                $media = $user->addMediaFromRequest('avatar')->toMediaCollection('images');
                // Get the URL of the stored media
                $imageUrl = $media->getUrl();
            } else {
                // If no avatar is uploaded, set the URL to null or some default value
                $imageUrl = null; // Or you can set a default image URL here
            }

            // Append the avatar URL to the user data
            $userData['avatar'] = $imageUrl;

            // Return a success response with the created user data
            return ApiResponse::success([
                'message' => 'User created successfully',
                'user' => UserResource::make($userData) // Format the user data using UserResource
            ]);
        } catch (\Throwable $th) {
            // Return an error response if an exception occurs
            return ApiResponse::error($th->getMessage());
        }
    }


    // Update an existing user
    public function update(UpdateUserRequest $request, $id)
    {
        // Try to update the user
        try {
            // Validate the incoming request data
            $userData = $request->validated();

            // Update the user data using UserRepository
            $success = $this->userRepo->update($id, $userData);

            // Return a success response with a message and the updated user data
            return ApiResponse::success([
                'message' => 'User updated successfully',
                'product' => $success // Assuming `$success` contains the updated user data
            ]);
        } catch (\Throwable $th) {
            // Return an error response if an exception occurs
            return ApiResponse::error($th->getMessage());
        }
    }


    // Delete a user by ID
    public function destroy($id)
    {
        // Delete the user by ID using UserRepository
        $this->userRepo->delete($id);

        // Return a success response with a message
        return ApiResponse::success([
            'message' => 'Deleted Successfully'
        ]);
    }
}
