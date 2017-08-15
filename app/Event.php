<?php

namespace App;

use Mail;
use Carbon\Carbon;
use App\Mail\NewEvent;
use App\Traits\Commentable;
use App\Traits\Subscribable;
use App\Filters\EventFilters;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use RecordsActivity, Commentable, Subscribable;

    /**
     * The attributes that are converted to Carbon instances.
     *
     * @var string
     */
    protected $dates = ['start_time', 'end_time'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'place_id',
        'location',
        'description',
        'user_id',
        'profile',
        'facebook_id',
        'meetup_id'
    ];

    protected $casts = [
        'profile' => 'json',
    ];

    protected $with = ['tags'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_subscribed_to'];

    /**
     * Boot the event instance.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($event) {
            if (!$event->user->isAdmin()) {
                Mail::to(env('MAIL_CONTACT_ADDRESS'))->send(new NewEvent($event));
            }
        });
    }

    public function url()
    {
        return "/events/{$this->id}";
    }

    /**
     * Place relationship.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    /**
     * Tags relationship.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * User relationship.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Apply all relevant filters.
     *
     * @param  Builder       $query
     * @param  EventFilters $filters
     * @return Builder
     */
    public function scopeFilter($query, EventFilters $filters)
    {
        return $filters->apply($query)
                       ->orderBy('start_time', 'asc')
                       ->with('place', 'tags', 'user');
    }
}
