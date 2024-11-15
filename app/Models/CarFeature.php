<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'gear',
        'engine',
        'color',
        'seats',
        'doors',
        'luggage',
        'sensors',
        'bluetooth',
        'gcc',
        'camera',
        'lcd',
        'safety',
        'radio',
        'Mb3_CD',
        'car_id',
    ];

    /**
     * Get the car that owns the features.
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
