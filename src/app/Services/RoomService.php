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

    public function getById(int $id): Room
    {
        return Room::findOrFail($id);
    }

    public function store(array $data): Room
    {
        $room = Room::create($data);

        return $room;
    }

    public function update(int $id, array $data): Room
    {
        $room = $this->getById($id);

        $room->update($data);

        return $room;
    }

    public function destroy(int $id): bool
    {
        return $this->getById($id)->delete();
    }
}
