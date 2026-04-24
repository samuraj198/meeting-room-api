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

    public function getById(int $id): ?Room
    {
        return Room::find($id);
    }

    public function store(array $data): Room
    {
        $room = Room::create($data);

        return $room;
    }

    public function update(int $id, array $data): ?Room
    {
        $room = $this->getById($id);

        if ($room == null) {
            return null;
        }

        $room->update($data);

        return $room;
    }

    public function destroy(int $id): ?bool
    {
        $room = $this->getById($id);

        if ($room == null) {
            return null;
        }

        return $room->delete();
    }
}
