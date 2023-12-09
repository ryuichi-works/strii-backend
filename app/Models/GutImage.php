<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GutImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'title'
    ];

    public function guts() {
        return $this->hasMany(Gut::class, 'image_id', 'id');
    }

    public function maker() {
        return $this->belongsTo(Maker::class);
    }
}
