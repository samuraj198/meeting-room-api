<?php

namespace App\Services;

use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class RoomService
{
    public function getActiveRooms(int $page): array
    {
        $cacheKey = 'rooms.active.page.' . $page;

        return Cache::tags(['rooms.active'])
            ->remember($cacheKey, 3600, function () use ($page) {
                $paginator = Room::where('is_active', true)->paginate(15, ['*'], 'page', $page);

                return [
                    'items' =>  collect($paginator->items())->toArray(),
                    'total' => $paginator->total(),
                    'per_page' => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                ];
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
                        $query->where('status', '!=', 'cancelled')
                            ->where('start_time', '<', $endDateTime)
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
