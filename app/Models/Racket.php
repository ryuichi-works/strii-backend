<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Racket extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ja',
        'name_en',
        'maker_id',
        'image_id',
        'need_posting_image'
    ];

    public function maker()
    {
        return $this->belongsTo(Maker::class);
    }

    public function racketImages()
    {
        return $this->hasMany(RacketImage::class, 'id', 'image_id');
    }
}
