<?php

namespace App;

use App\Notifications\NewComment;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the user associated with the subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject associated with the subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Notify the related user that the subject was updated.
     *
     * @param \App\Reply $comment
     */
    public function notify($comment)
    {
        $this->user->notify(new NewComment($comment, $this->subject));
    }
}
