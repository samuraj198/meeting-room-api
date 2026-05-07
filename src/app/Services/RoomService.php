<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Ramsey\Collection\Collection;

class RoomService
{
    public function getActiveRooms(int $page): LengthAwarePaginator
    {
        return Cache::tags(['rooms'])
            ->remember('rooms.active.page.' . $page, 3600, function () use ($page) {
                return Room::where('is_active', true)->paginate(15, ['*'], 'page', $page);
            });
    }

    public function getAvailableRooms(string $date, string $startTime, string $endTime): Collection
    {
        $startDateTime = Carbon::parse("{$date} {$startTime}");
        $endDateTime = Carbon::parse("{$date} {$endTime}");

        $cacheKey = 'rooms.available.' . md5($date . $startTime . $endTime);

        return Cache::tags(['rooms.available'])->remember($cacheKey, 300,
            function () use ($startDateTime, $endDateTime) {
                return Room::where('is_active', true)
                    ->whereDoesntHave('bookings', function ($query) use ($startDateTime, $endDateTime) {
                        $query->where('start_time', '<', $endDateTime)
                        ->where('end_time', '>', $startDateTime);
                    })->get();
            });
    }

    public function store(array $data): Room
    {
        $room = Room::create($data);
        Cache::tags(['rooms'])->flush();

        return $room;
    }

    public function update(Room $room, array $data): Room
    {
        $room->update($data);
        Cache::tags(['rooms'])->flush();

        return $room;
    }

    public function destroy(Room $room): bool
    {
        $del = $room->delete();
        Cache::tags(['rooms'])->flush();

        return $del;
    }
}
