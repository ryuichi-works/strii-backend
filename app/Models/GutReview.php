<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GutReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'match_rate',
        'pysical_durability',
        'performance_durability',
        'review'
    ];

    public function myEquipment()
    {
        return $this->belongsTo(MyEquipment::class,'equipment_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
