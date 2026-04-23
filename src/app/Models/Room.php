<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function favoriteBy()
    {
        return $this->belongsToMany(User::class, 'room_user')->withTimestamps();
    }
}
