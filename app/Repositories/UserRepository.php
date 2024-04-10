<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepository
{
    function all();

    function findOrFail(int $id): ?User;

    function create(array $data): ?User;

    function update(int $id, array $data): bool;

    function delete(int $id): bool;
}
