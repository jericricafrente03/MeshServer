<?php

namespace Database\Factories\General\Body\Hospitality;

use App\Models\General\Body\Hospitality\FacilityCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\General\Body\Hospitality\FacilityCategory>
 */
class FacilityCategoryFactory extends Factory
{
    protected $model = FacilityCategory::class;
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
            'img_preview_uri' => "{$systemUrl}storage/upload/facility_categories/" . $this->faker->lexify('??????????????????????????????') . '.png',
            'img_uri' => "{$systemUrl}storage/upload/facility_categories/" . $this->faker->lexify('??????????????????????????????') . '.png',
            'order_no' => $this->faker->randomNumber(),
        ];
    }
}
