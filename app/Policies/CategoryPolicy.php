<?php

namespace App\Policies;

use App\Models\User;

class CategoryPolicy
{
    function list(?User $user): bool
    {
        return true;
    }

    function view(?User $user): bool
    {
        return true;
    }

    function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    function update(User $user): bool
    {
        return $this->create($user);
    }

    function delete(User $user): bool
    {
        return $this->create($user);
    }
}
