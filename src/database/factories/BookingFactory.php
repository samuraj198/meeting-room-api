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
        $start = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $end = (clone $start)->modify('+' . $this->faker->numberBetween(1, 3) . ' hours');

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
