<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RacketImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'title'
    ];

    public function rackets()
    {
        return $this->hasMany(Racket::class, 'image_id','id');
    }
}
