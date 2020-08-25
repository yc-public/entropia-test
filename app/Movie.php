<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Movie extends Model
{
    protected $fillable = [
        'producer_id', 'name', 'release_year', 'plot', 'poster'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getPosterUrlAttribute() {
        return Storage::url($this->poster);
    }

    /**
     * The relationships
     *
     */
    public function producer() {
        return $this->belongsTo('App\User', 'producer_id');
    }

    public function actors() {
        return $this->belongsToMany('App\User', 'movie_actor', 'movie_id', 'actor_id');
    }
}
