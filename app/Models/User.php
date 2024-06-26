<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Listing::class, 'user_favorites')->withPivot('user_id', 'listing_id');
    }

    public function bought()
    {
        return $this->belongsToMany(Listing::class, 'user_bought')->withPivot('user_id', 'listing_id', 'created_at');
    }
    
    public function listings()
    {
        return $this->hasMany('App\Models\Listing');
    }

    public function bids()
    {
        return $this->hasMany('App\Models\Bid');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
