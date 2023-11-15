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

    public function racket()
    {
        return $this->belongsTo(Racket::class, 'id','image_id');
    }
}
