<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_id', 'name'
    ];

    /**
     * Get the cars for the category.
     */
    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
