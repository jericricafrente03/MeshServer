<?php

namespace Database\Factories\SystemSettings;

use App\Models\SystemSettings\SystemConfig;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SystemSettings\SystemConfig>
 */
class SystemConfigFactory extends Factory
{
    protected $model = SystemConfig::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get SYSTEM_URL from the environment
        $systemUrl = config('app.system_url');

        $randomText = [
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

        return [
            'id' => $this->faker->randomNumber(),
            'name' => $this->faker->randomElement($randomText),
            'description' => $this->faker->text(200),
            'street' => $this->faker->paragraph(5, true),
            'city' => $this->faker->randomElement($randomText),
            'country_code' => $this->faker->countryCode,
            'time_zone' => $this->faker->timezone,
            'email' => $this->faker->unique()->safeEmail,
            'currency' => $this->faker->currencyCode,
            'website' => $this->faker->url,
            'logo' => "{$systemUrl}upload/systemConfig/" . $this->faker->lexify('??????????????????????????????') . '.png',
            'lat' => $this->faker->latitude,
            'lon' => $this->faker->longitude,
            'welcome_message' => $this->faker->randomElement($randomText),
            'max_idle' => $this->faker->numberBetween(1, 50),
        ];
    }
}
