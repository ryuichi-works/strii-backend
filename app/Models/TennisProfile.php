<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TennisProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gender',
        'my_racket_id',
        'grip_form',
        'height',
        'age',
        'physique',
        'experience_period',
        'frequency',
        'play_style',
        'favarit_shot',
        'weak_shot'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function racket()
    {
        return $this->belongsTo(Racket::class, 'my_racket_id', 'id');
    }
}
