<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [ 'user_id', 'car_id', 'with_driver', 'start', 'end', 'status',];

    /**
     * Get the user associated with the reservation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the car associated with the reservation.
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
