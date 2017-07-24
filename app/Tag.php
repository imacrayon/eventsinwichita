<?php

namespace App;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The attributes that hidden from displaying.
     *
     * @var array
     */
    protected $hidden = ['pivot'];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'name' => ['required']
    ];

    /**
     * Disable timestamps.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Events relationship.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    /**
     * Locations relationship.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function places()
    {
        return $this->belongsToMany(Place::class);
    }

    public function filterUrl()
    {
        $query = request()->query();

        $tags = array_get($query, 'tags', []);

        $tags = is_array($tags) ? $tags : [$tags];

        if (($key = array_search($this->id, $tags)) !== false) {
            unset($tags[$key]);
        } else {
            $tags[] = $this->id;
        }

        if (count($tags)) {
            $query['tags'] = $tags;
        } else {
            unset($query['tags']);
        }

        return request()->url() . (count($query) ? '?' . http_build_query($query) : '');
    }
}
