<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = ['name', 'description', 'price_per_night', 'image'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}