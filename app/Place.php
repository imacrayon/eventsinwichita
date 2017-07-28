<?php

namespace App;

use App\Traits\Sluggable;
use App\Traits\Commentable;
use App\Traits\Subscribable;
use App\Filters\PlaceFilters;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use Sluggable, RecordsActivity, Commentable, Subscribable;

    protected $appends =['map', 'upcoming_events_count', 'is_subscribed_to'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'street',
        'city',
        'state',
        'zip',
        'longitude',
        'latitude',
        'facebook_id',
        'meetup_id',
        'profile'
    ];

    protected $casts = [
        'profile' => 'json',
    ];

    protected $with = ['tags'];

    public function url()
    {
        return "/places/{$this->id}";
    }

    /**
     * Events relationship.
     *
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class);
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

    public function getMapAttribute()
    {
        return 'http://maps.apple.com/maps?q=' . $this->latitude . ',' . $this->longitude;
    }

    /**
     * Apply all relevant filters.
     *
     * @param  Builder       $query
     * @param  PlaceFilters $filters
     * @return Builder
     */
    public function scopeFilter($query, PlaceFilters $filters)
    {
        return $filters->apply($query)
                       ->orderByRaw("REPLACE(`name`, 'The ', '')", 'asc')
                       ->with('tags', 'user');
    }

    public function upcomingEventsCount()
    {
        return $this->events()->where('end_time', '>=', new \DateTime())->count();
    }

    public function getUpcomingEventsCountAttribute()
    {
        return $this->upcomingEventsCount();
    }
}
