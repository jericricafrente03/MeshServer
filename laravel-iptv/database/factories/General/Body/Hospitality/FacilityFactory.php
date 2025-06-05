<?php

namespace Database\Factories\General\Body\Hospitality;

use App\Models\General\Body\Hospitality\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\General\Body\Hospitality\Facility>
 */
class FacilityFactory extends Factory
{
    protected $model = Facility::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get SYSTEM_URL from the environment
        $systemUrl = config('app.system_url');

        return [
            'id' => $this->faker->randomNumber(),
            'name' => $this->faker->company(),
            'description' => $this->faker->text(200),
            'unit_price' => $this->faker->randomFloat(2, 10, 1000), // Generates a price between 10 and 1000 with 2 decimal places
            'category_id' => $this->faker->randomNumber(),
            'img_uri' => "{$systemUrl}storage/upload/facilities/" . $this->faker->lexify('??????????????????????????????') . '.png',
        ];
    }
}
