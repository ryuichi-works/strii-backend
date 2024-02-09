<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ja',
        'name_en'
    ];

    public function guts() {
        return $this->hasMany(Gut::class);
    }

    public function rackets()
    {
        return $this->hasMany(Racket::class);
    }

    public function gutImages()
    {
        return $this->hasMany(GutImage::class);
    }

    public function racketImages()
    {
        return $this->hasMany(RacketImage::class);
    }

    public function racketSeries()
    {
        return $this->hasMany(RacketSeries::class);
    }
}
