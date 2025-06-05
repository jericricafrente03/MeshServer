<?php

namespace Database\Factories\General\Body\Hospitality;

use App\Models\General\Body\Hospitality\HospitalityItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class HospitalityItemFactory extends Factory
{
    protected $model = HospitalityItem::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $systemUrl = config('app.system_url');

        return [
            'id' => $this->faker->randomNumber(),
            'name' => $this->faker->company(),
            'description' => $this->faker->text(200),
            'unit_price' => $this->faker->randomFloat(2, 10, 1000), // Generates a price between 10 and 1000 with 2 decimal places
            'img_uri' => "{$systemUrl}storage/upload/item_request/" . $this->faker->lexify('??????????????????????????????') . '.png',
        ];
    }
}
