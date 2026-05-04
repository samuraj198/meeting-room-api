<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;

class RoomPolicy
{
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Room $room): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Room $room): bool
    {
        return $user->role === 'admin';
    }
}
