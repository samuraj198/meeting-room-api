<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Models\Booking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendBookingConfirmationNotification implements ShouldQueue
{
    public function __construct()
    {}

    /**
     * Handle the event.
     */
    public function handle(BookingCreated $event): void
    {
        Log::info('Комната ' . $event->booking->room_id . ' забронирована');
    }
}
