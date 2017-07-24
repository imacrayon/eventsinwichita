<?php

namespace App\Traits;

trait Commentable
{
    /**
     * Boot the model.
     */
    protected static function bootCommentable()
    {
        static::deleting(function ($subject) {
            $subject->comments->each->delete();
        });
    }

    /**
     * Get all of the comments.
     */
    public function comments()
    {
        return $this->morphMany('App\Comment', 'subject');
    }

    /**
     * Add a comment.
     *
     * @param  array $comment
     * @return Model
     */
    public function addComment($comment)
    {
        $comment = $this->comments()->create($comment);
        $this->notifySubscribers($comment);
        if (!$this->is_subscribed_to) {
            $this->subscribe();
        }

        return $comment;
    }

    /**
     * Notify all subscribers about a new comment.
     *
     * @param \App\Reply $reply
     */
    public function notifySubscribers($comment)
    {
        $this->subscriptions
            ->where('user_id', '!=', $comment->user_id)
            ->each
            ->notify($comment);
    }

}
