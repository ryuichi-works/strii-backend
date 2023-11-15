<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gut extends Model
{
    use HasFactory;

    public function maker() {
        return $this->belongsTo(Maker::class);
    }

    public function gutImages() {
        return $this->hasMany(GutImage::class, 'id', 'image_id');
    }
}
