<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'capacity' => $this->faker->numberBetween(1, 20),
            'description' => $this->faker->paragraph(),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
