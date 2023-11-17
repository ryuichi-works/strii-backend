<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyEquipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_height',
        'user_age',
        'experience_period',
        'racket_id',
        'stringing_way',
        'main_gut_id',
        'main_gut_guage',
        'main_gut_tension',
        'cross_gut_id',
        'cross_gut_guage',
        'cross_gut_tension',
        'new_gut_date',
        'change_gut_date',
        'comment'
    ];

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
