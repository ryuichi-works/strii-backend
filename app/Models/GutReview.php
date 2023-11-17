<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GutReview extends Model
{
    use HasFactory;

    public function myEquipment()
    {
        return $this->belongsTo(MyEquipment::class,'equipment_id', 'id');
    }
}
