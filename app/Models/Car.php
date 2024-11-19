<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [ 'trademark', 'model', 'delivery', 'details', 'rent_id', 'insurance', 'KMs', 'deposit', 'min_age', 'category_id','is_hidden' ];

    /**
     * Get the rent associated with the car.
     */
    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }

    /**
     * Get the category associated with the car.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function reservations() { return $this->hasMany(Reservation::class);
    }
    public function features()
    {
        return $this->hasOne(CarFeature::class);
    }
    public function images() { return $this->hasMany(CarImage::class); }
}
