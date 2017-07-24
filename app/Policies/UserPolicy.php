<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given appUser can be edited by the user.
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
     * Determine if the given appUser can be updated by the user.
     *
     * @param  \App\User  $user
     * @param  \App\User  $appUser
     * @return bool
     */
    public function update(User $user, User $appUser)
    {
        return $user->id === $appUser->id;
    }

    /**
     * Determine if the given appUser can be destroyed by the user.
     *
     * @param  \App\User  $user
     * @param  \App\User $appUser
     * @return bool
     */
    public function destroy(User $user, User $appUser)
    {
        return $user->id === $appUser->id;
    }
}
