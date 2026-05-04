<?php

namespace App\Services;

use App\Models\Room;
use Illuminate\Pagination\LengthAwarePaginator;

class RoomService
{
    public function getActiveRooms(): LengthAwarePaginator
    {
        return Room::where('is_active', true)->paginate(15);
    }

    public function store(array $data): Room
    {
        $room = Room::create($data);

        return $room;
    }

    public function update(Room $room, array $data): Room
    {
        $room->update($data);

        return $room;
    }

    public function destroy(Room $room): bool
    {
        return $room->delete();
    }
}
