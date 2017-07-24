<?php

namespace App;

use App\User;
use App\Filters\ActivityFilters;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    protected $with = ['subject'];

    public function subject()
    {
        return $this->morphTo();
    }

    public static function feed(User $user, $take = 50)
    {
        return static::where('user_id', $user->id)
            ->latest()
            ->with('subject')
            ->take($take)
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('Y-m-d');
            });
    }

    /**
     * Apply all relevant filters.
     *
     * @param  Builder       $query
     * @param  PlaceFilters $filters
     * @return Builder
     */
    public function scopeFilter($query, ActivityFilters $filters)
    {
        return $filters->apply($query)
                       ->orderBy('created_at', 'desc')
                       ->with('subject');
    }

    public static function getIpAddress()
    {
        return isset($_SERVER['HTTP_CF_CONNECTING_IP'])
            ? $_SERVER['HTTP_CF_CONNECTING_IP']
            : (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0');
    }

    public static function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT'])
            ? $_SERVER['HTTP_USER_AGENT']
            : 'unknown';
    }

    public static function getCountry()
    {
        return isset($_SERVER['HTTP_CF_IPCOUNTRY'])
            ? $_SERVER['HTTP_CF_IPCOUNTRY']
            : 'unknown';
    }
}
