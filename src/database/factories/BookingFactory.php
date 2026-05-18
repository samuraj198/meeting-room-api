<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = now()->addDays(random_int(1, 4))->setHour(random_int(9, 18))
            ->setMinute(0)
            ->setSecond(0);
        $end = $start->copy()->addHours($this->faker->numberBetween(1, 3));

        return [
            'user_id' => User::factory(),
            'room_id' => Room::factory(),
            'start_time' => $start,
            'end_time' => $end,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
            'purpose' => $this->faker->sentence(),
        ];
    }
}
