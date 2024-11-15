<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id', 'image'
    ];

    /**
     * Get the car associated with the image.
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    public function images() { return $this->hasMany(CarImage::class); }
}
