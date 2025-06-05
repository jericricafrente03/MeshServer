<?php

namespace Database\Factories\General\Body\Hospitality;

use App\Models\General\Body\Hospitality\FnbCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\General\Body\Hospitality\FnbCategory>
 */
class FnbCategoryFactory extends Factory
{
    protected $model = FnbCategory::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Breakfast', 'Lunch', 'Dinner'];

        return [
            'id' => $this->faker->randomNumber(),
            'name' => $this->faker->randomElement($categories),
            'description' => $this->faker->text(200),
            'order_no' => $this->faker->randomNumber(),
        ];
    }
}
