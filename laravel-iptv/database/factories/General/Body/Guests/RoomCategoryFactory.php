<?php

namespace Database\Factories\General\Body\Guests;

use App\Models\General\Body\Guests\RoomCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\General\Body\Guests\RoomCategory>
 */
class RoomCategoryFactory extends Factory
{
    protected $model = RoomCategory::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->randomNumber(),
            'name' => $this->faker->company(),
            'description' => $this->faker->text(200),
            'order_no' => $this->faker->randomNumber(),
        ];
    }
}
