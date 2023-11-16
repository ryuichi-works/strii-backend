<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyEquipment extends Model
{
    use HasFactory;

    protected $table = 'my_equipments';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function racket()
    {
        return $this->belongsTo(Racket::class);
    }

    public function mainGut()
    {
        return $this->belongsTo(Gut::class, 'main_gut_id', 'id');
    }

    public function crossGut()
    {
        return $this->belongsTo(Gut::class, 'cross_gut_id', 'id');
    }
}
