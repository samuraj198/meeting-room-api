<?php

namespace App\Jobs;

use App\Models\Booking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendBookingReminder implements ShouldQueue
{
    use Queueable;

    public function __construct(private Booking $booking)
    {}

    public function handle(): void
    {
        Log::info('Booking reminder');
    }
}
