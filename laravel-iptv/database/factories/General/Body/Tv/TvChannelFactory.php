<?php

namespace Database\Factories\General\Body\Tv;

use App\Models\General\Body\Tv\TvChannel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\General\Body\Tv\TvChannel>
 */
class TvChannelFactory extends Factory
{
    protected $model = TvChannel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tvChannels = [
            'FOX MOVIES HD',
            'Paramount',
            'Cartoon Network',
            'HBO HD',
            'NATIONAL GEOGRAPHIC CHANNEL',
            'DISCOVERY CHANNEL',
            'FOX NEWS HD',
            'BBC Earth',
            'CNN International HD',
            'ANIMAL PLANET',
            'NICKELODEON',
            'ASIAN CRUSH',
            'EURO NEWS',
        ];

        // Get SYSTEM_URL from the environment
        $systemUrl = config('app.system_url');

        return [
            'id' => $this->faker->randomNumber(),
            'channel' => $this->faker->numberBetween(1, 150),
            'name' => $this->faker->randomElement($tvChannels),
            'description' => 'Sample Description',
            'channel_uri' => $this->faker->randomNumber(),
            'category_id' => $this->faker->randomNumber(),
            'order_no' => $this->faker->numberBetween(1, 50),
            'image_uri' => "{$systemUrl}storage/upload/tv_channels/" . $this->faker->lexify('??????????????????????????????') . '.png',
        ];
    }
}
