<?php

namespace App\Services;

use App\Exceptions\BookingAlreadyCancelledException;
use App\Exceptions\RoomAlreadyBookedException;
use App\Jobs\SendBookingConfirmation;
use App\Jobs\SendBookingReminder;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use http\Client\Curl\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function getAll(): Collection
    {
        return Booking::with('room')->active()->latest()->get();
    }

    public function getUserBookings(int $userId): Collection
    {
        return Booking::with('room')
            ->forUser($userId)
            ->latest()
            ->get();
    }

    public function store(array $data, string $timezone,int $userId): Booking
    {
        return DB::transaction(function () use ($data, $timezone, $userId) {
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
                'start_time' => Carbon::parse($data['start_time'], $timezone)->utc(),
                'end_time' => Carbon::parse($data['end_time'], $timezone)->utc(),
                'purpose' => $data['purpose'] ?? null,
                'status' => 'pending',
            ]);

            SendBookingConfirmation::dispatch($booking);

            $reminderTime = $booking->start_time->copy()->subMinutes(15);

            if ($reminderTime->gt(now())) {
                SendBookingReminder::dispatch($booking)->delay($reminderTime);
            }

            return $booking;
        });
    }

    public function destroy(Booking $booking): bool
    {
        return $booking->delete();
    }

    public function cancel(Booking $booking): Booking
    {
        if ($booking->status == 'cancelled') {
            throw new BookingAlreadyCancelledException();
        }

        $booking->update(['status' => 'cancelled']);

        return $booking;
    }
}
