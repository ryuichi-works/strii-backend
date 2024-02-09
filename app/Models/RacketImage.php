<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RacketImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'title',
        'maker_id'
    ];

    public function rackets()
    {
        return $this->hasMany(Racket::class, 'image_id','id');
    }

    public function maker()
    {
        return $this->belongsTo(Maker::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'posting_user_id', 'id');
    }
}
