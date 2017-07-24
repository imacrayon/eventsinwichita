<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'settings'
    ];

    protected $casts = [
        'settings' => 'json',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static $recordEvents = ['created'];

    public function url()
    {
        return "/users/{$this->id}";
    }

    /**
     * Check if user is a Admin
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->role === 1;
    }

    /**
     * The user's events.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * The user's places.
     */
    public function places()
    {
        return $this->hasMany(Place::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
}
