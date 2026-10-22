<?php

namespace App\Policies;

use App\Models\User;

// Only the admin can see and manage users.
class UserPolicy
{
    function list(User $user): bool
    {
        return $user->isAdministrator();
    }

    function view(User $user): bool
    {
        return $user->isAdministrator();
    }

    function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    function update(User $user): bool
    {
        return $user->isAdministrator();
    }

    // The admin cannot change their own role or delete themselves.
    function updateRole(User $user, User $target): bool
    {
        return $user->isAdministrator() && $user->id !== $target->id;
    }

    function delete(User $user, User $target): bool
    {
        return $user->isAdministrator() && $user->id !== $target->id;
    }
}
