<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Notifiable, SoftDeletes, Authenticatable, Authorizable;

    const ROLE_ADMIN = 'admin';

    protected $casts = [
        'settings' => 'collection',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function scopeAdmins($query)
    {
        $query->where('role', self::ROLE_ADMIN);
    }

    public function getAvatarAttribute()
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower($this->email)).'?s=128&d=mm';
    }
}
