<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RacketSeries extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ja',
        'name_en',
        'maker_id',
    ];

    public static function rules()
    {
        return [
            'name_ja' => ['required', 'string', 'max:30'],
            'name_en' => ['string', 'max:30'],
            'maker_id' => ['required', 'integer', 'exists:makers,id'],
        ];
    }

    public function rackets()
    {
        return $this->hasMany(Racket::class, 'series_id', 'id');
    }

    public function maker()
    {
        return $this->belongsTo(Maker::class);
    }
}
