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

    public function gut() {
        return $this->belongsTo(Gut::class, 'id');
    }
}
