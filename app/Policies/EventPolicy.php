<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    // ?User: guests (not logged in) can see events too.
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
        return $user->isAdministrator() || $user->isSmo();
    }

    // Admin can change any event; an SMO only the events they created.
    function update(User $user, Event $event): bool
    {
        return $user->isAdministrator()
            || ($user->isSmo() && $event->creator_id === $user->id);
    }

    function delete(User $user, Event $event): bool
    {
        return $this->update($user, $event);
    }

    // A student can register only for events of their own faculty, once.
    function register(User $user, Event $event): bool
    {
        return $user->isStudent()
            && $user->faculty() === $event->faculty
            && !$this->isRegistered($user, $event);
    }

    function unregister(User $user, Event $event): bool
    {
        return $user->isStudent() && $this->isRegistered($user, $event);
    }

    private function isRegistered(User $user, Event $event): bool
    {
        return $event->students()->where('users.id', $user->id)->exists();
    }
}
