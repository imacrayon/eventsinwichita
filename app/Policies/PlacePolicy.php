<?php

namespace App\Policies;

use App\User;
use App\Place;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlacePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given place can be edited by the user.
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
     * Determine if the given place can be updated by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Place  $place
     * @return bool
     */
    public function update(User $user, Place $place)
    {
        return $user->id === $place->user_id;
    }

    /**
     * Determine if the given place can be destroyed by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Place $place
     * @return bool
     */
    public function destroy(User $user, Place $place)
    {
        return $user->id === $place->user_id;
    }
}
