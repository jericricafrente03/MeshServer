<?php

namespace Database\Factories\General\Body\Hospitality;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\General\Body\Hospitality\TopRecommendedFnb>
 */
class TopRecommendedFnbFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $systemUrl = config('app.system_url');

        $foods = ['Pizza', 'Burger', 'Sushi', 'Pasta', 'Tacos', 'Steak', 'Salad', 'Ramen', 'Dumplings', 'Curry'];

        return [
            'id' => $this->faker->randomNumber(),
            'name' => $this->faker->randomElement($foods),
            'description' => $this->faker->text(200),
            'unit_price' => $this->faker->randomFloat(2, 10, 1000), // Generates a price between 10 and 1000 with 2 decimal places
            'category_id' => 0,
            'popularity_score' => $this->faker->randomNumber(),
            'img_uri' => "{$systemUrl}storage/upload/facilities/" . $this->faker->lexify('??????????????????????????????') . '.png',
        ];
    }
}
