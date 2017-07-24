<?php

namespace App\Policies;

use App\User;
use App\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given comment can be edited by the user.
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
     * Determine if the given comment can be updated by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return bool
     */
    public function update(User $user, Comment $comment)
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Determine if the given comment can be destroyed by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Event $event
     * @return bool
     */
    public function destroy(User $user, Comment $comment)
    {
        return $user->id === $comment->user_id
            || $user->id === $comment->subject->user_id;
    }
}
