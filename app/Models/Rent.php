<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily', 'weekly', 'monthly', 'daily_with_driver', 'weekly_with_driver', 'monthly_with_driver'
    ];

    /**
     * Get the cars for the rent.
     */
    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
