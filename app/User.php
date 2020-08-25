<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'sex_id', 'name', 'dob', 'bio',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'date',
    ];

    /**
     * Scope a query
     *
     */
    public function scopeIsActor($query) {
        return $query->whereHas('role', function($query) {
            $query->where('name', 'actor');
        });
    }

    public function scopeIsProducer($query) {
        return $query->whereHas('role', function($query) {
            $query->where('name', 'producer');
        });
    }

    /**
     * The relationships
     *
     */
    public function role() {
        return $this->belongsTo('App\Role');
    }

    public function sex() {
        return $this->belongsTo('App\Sex');
    }

    public function movies() {
        return $this->belongsToMany('App\Movie', 'movie_actor', 'actor_id', 'movie_id');
    }
}
