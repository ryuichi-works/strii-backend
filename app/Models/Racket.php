<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Racket extends Model
{
    use HasFactory;

    public function maker()
    {
        return $this->belongsTo(Maker::class);
    }

    public function racketImages()
    {
        return $this->hasMany(RacketImage::class, 'id', 'image_id');
    }
}
