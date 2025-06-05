<?php

namespace Database\Factories\Analytics;

use App\Models\Analytics\Analytic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Analytics\Analytic>
 */
class AnalyticFactory extends Factory
{
    protected $model = Analytic::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = [
            'Application',
            'TV',
            
        ];
        return [
            'id' => $this->faker->randomNumber(),
            // 'channel' => $this->faker->numberBetween(1, 150),
            'category_key' => $this->faker->randomElement($name),
            'type_id' => $this->faker->numberBetween(1, 50),
            'item_id' => $this->faker->numberBetween(1, 50),
            'room_id' => $this->faker->numberBetween(1, 50),
        
        ];
    }
}
