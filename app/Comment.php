<?php

namespace App;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use RecordsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'subject_type',
        'subject_id',
        'body'
    ];

    protected $with = ['subject'];

    protected static $recordEvents = ['created'];

    public function subject()
    {
        return $this->morphTo();
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

    public function url()
    {
        return $this->subject->url() . "#comment-{$this->id}";
    }
}
