<?php

namespace Database\Factories\General\Body\Guests;

use App\Models\General\Body\Guests\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\General\Body\Guests\Room>
 */
class RoomFactory extends Factory
{
    protected $model = Room::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->randomNumber(),
            'name' => $this->faker->randomNumber(),
            'category_id' => $this->faker->randomNumber(),
            'status' => [
                'id' => $this->faker->randomNumber(),
                'name' => $this->faker->randomElement(['Available', 'Occupied']),
            ],
        ];
    }
}
