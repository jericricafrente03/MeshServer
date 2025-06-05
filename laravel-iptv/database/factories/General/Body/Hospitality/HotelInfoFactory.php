<?php

namespace Database\Factories\General\Body\Hospitality;

use App\Models\General\Body\Hospitality\HotelInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\General\Body\Hospitality\HotelInfo>
 */
class HotelInfoFactory extends Factory
{
    protected $model = HotelInfo::class;
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
            'img_uri' => "{$systemUrl}storage/upload/hotel_infos/" . $this->faker->lexify('??????????????????????????????') . '.png',
            'order_no' => $this->faker->randomNumber(),
        ];
    }
}
