<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RacketSeries extends Model
{
    use HasFactory;

    public function rackets()
    {
        return $this->hasMany(Racket::class, 'series_id', 'id');
    }
}
