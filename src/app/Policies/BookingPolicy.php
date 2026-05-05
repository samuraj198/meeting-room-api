<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function update(User $user, Booking $booking)
    {
        return $user->id === $booking->user_id || $user->role === 'admin';
    }

    public function delete(User $user, Booking $booking)
    {
        return $user->id === $booking->user_id || $user->role === 'admin';
    }

    public function cancel(User $user, Booking $booking)
    {
        return $user->id === $booking->user_id || $user->role === 'admin';
    }

    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Booking $booking)
    {
        return $user->id === $booking->user_id || $user->role === 'admin';
    }
}
