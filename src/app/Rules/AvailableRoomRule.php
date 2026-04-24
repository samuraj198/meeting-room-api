<?php

namespace App\Rules;

use App\Models\Booking;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class AvailableRoomRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $startTime = request()->get('start_time');
        $endTime = request()->get('end_time');

        if (!$startTime || !$endTime) {
            return;
        }

        $exists = Booking::where('room_id', $value)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime);

        if ($exists) {
            $fail('Эта комната уже забронирована на выбранное время.');
        }
    }
}
