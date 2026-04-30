<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'start_time',
        'end_time',
        'status',
        'purpose',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeActive($query): Builder
    {
        return $query->where('status', '!=', 'cancelled');
    }

    public function scopeUpcoming($query): Builder
    {
        return $query->where('start_time', '>', now());
    }

    public function scopeForUser($query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}
