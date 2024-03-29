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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'file_path'
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
    ];

    public function tennisProfile()
    {
        return $this->hasOne(TennisProfile::class);
    }

    public function myEquipments()
    {
        return $this->hasMany(MyEquipment::class);
    }

    public function gutReviews()
    {
        return $this->hasMany(GutReview::class);
    }

    public function rackets()
    {
        return $this->hasMany(Racket::class, 'posting_user_id', 'id');
    }

    public function racket_images()
    {
        return $this->hasMany(RacketImage::class, 'posting_user_id', 'id');
    }
}
