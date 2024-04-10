<?php

namespace App\Repositories;

use App\Models\User;

class EloquentUserRepository implements UserRepository
{
    // Retrieve all users with their associated products
    public function all()
    {
        return User::with('products')->get();
    }

    // Find a user by their ID or return null
    public function findOrFail(int $id): ?User
    {
        $user = User::find($id);

        return $user;
    }

    // Create a new user with the provided data
    public function create(array $data): ?User
    {
        $user = User::create($data);

        return $user;
    }

    // Update an existing user with the provided data
    public function update(int $id, array $data): bool
    {
        $user = User::find($id);

        // If user not found, return false
        if (!$user) {
            return false;
        }

        // Update user's data
        $user->update($data);

        return true;
    }

    // Delete a user by their ID
    public function delete(int $id): bool
    {
        $user = User::find($id);

        // If user not found, return false
        if (!$user) {
            return false;
        }

        // Delete the user
        $user->delete();

        return true;
    }
}
