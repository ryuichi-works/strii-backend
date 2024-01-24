<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gut extends Model
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

    public function gutImage()
    {
        return $this->belongsTo(GutImage::class, 'image_id', 'id');
    }

    public function myEquipmentsWithMainGut()
    {
        return $this->hasMany(MyEquipment::class, 'main_gut_id', 'id');
    }

    public function myEquipmentsWithCrossGut()
    {
        return $this->hasMany(MyEquipment::class, 'cross_gut_id', 'id');
    }
}
