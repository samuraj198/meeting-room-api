<?php

namespace App\Services;

use App\Exceptions\BookingAlreadyCancelledException;
use App\Exceptions\RoomAlreadyBookedException;
use App\Models\Booking;
use App\Models\Room;
use http\Client\Curl\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function getAll(): Collection
    {
        return Booking::with('room')->active()->latest()->get();
    }

    public function getById(int $id): Booking
    {
        return Booking::with('room')->findOrFail($id);
    }

    public function getUserBookings(int $userId): Collection
    {
        return Booking::with('room')
            ->forUser($userId)
            ->latest()
            ->get();
    }

    public function store(array $data, int $userId): Booking
    {
        return DB::transaction(function () use ($data, $userId) {
            $room = Room::where('id', $data['room_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $check = Booking::where('room_id', $data['room_id'])
                ->where('start_time', '<', $data['end_time'])
                ->where('end_time', '>', $data['start_time'])
                ->exists();

            if ($check) {
                throw new RoomAlreadyBookedException();
            }

            $booking = Booking::create([
                'user_id' => $userId,
                'room_id' => $data['room_id'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'purpose' => $data['purpose'] ?? null,
                'status' => 'pending',
            ]);

            return $booking;
        });
    }

    public function destroy(int $id): bool
    {
        return $this->getById($id)->delete();
    }

    public function cancel(int $id): Booking
    {
        $booking = $this->getById($id);

        if ($booking->status == 'cancelled') {
            throw new BookingAlreadyCancelledException();
        }

        $booking->update(['status' => 'cancelled']);

        return $booking;
    }
}
