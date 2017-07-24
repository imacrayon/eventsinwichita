<?php

namespace App\Traits;

trait Subscribable
{
    /**
     * Boot the model.
     */
    protected static function bootSubscribable()
    {
        static::deleting(function ($subject) {
            $subject->subscriptions->each->delete();
        });

        static::created(function ($subject) {
            $subject->subscribe($subject->user->id);
        });
    }

    /**
     * Subscribe a user to the current subject.
     *
     * @param  int|null $userId
     * @return $this
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    /**
     * Unsubscribe a user from the current subject.
     *
     * @param int|null $userId
     */
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    /**
     * A subjects subscriptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function subscriptions()
    {
        return $this->morphMany('App\Subscription', 'subject');
    }

    /**
     * Determine if the current user is subscribed to the subject.
     *
     * @return boolean
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }
}
