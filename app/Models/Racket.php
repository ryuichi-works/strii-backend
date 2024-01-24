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

    public function racketImage()
    {
        return $this->belongsTo(RacketImage::class,  'image_id', 'id');
    }

    public function tennisProfiles()
    {
        return $this->hasMany(TennisProfile::class, 'my_racket_id', 'id');
    }

    public function myEquipments()
    {
        return $this->hasMany(MyEquipment::class);
    }
}
