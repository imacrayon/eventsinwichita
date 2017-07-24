<?php

namespace App\Policies;

use App\User;
use App\Event;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given event can be edited by the user.
     *
     * @param  \App\User  $user
     * @param  string     $abiliy
     * @return bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine if the given event can be updated by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return bool
     */
    public function update(User $user, Event $event)
    {
        return $user->id === $event->user_id;
    }

    /**
     * Determine if the given event can be destroyed by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Event $event
     * @return bool
     */
    public function destroy(User $user, Event $event)
    {
        return $user->id === $event->user_id;
    }
}
