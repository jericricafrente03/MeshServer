<?php

namespace Database\Factories;

use App\Models\WeatherDailyForecast;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WeatherDailyForecast>
 */
class WeatherDailyForecastFactory extends Factory
{
    protected $model = WeatherDailyForecast::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $weatherDescriptions = [
            'Clear sky',
            'Mainly clear',
            'Partly cloudy',
            'Overcast',
            'Fog',
            'Depositing rime fog',
            'Drizzle: Light intensity',
            'Drizzle: Moderate intensity',
            'Drizzle: Dense intensity',
            'Freezing Drizzle: Light intensity',
            'Freezing Drizzle: Dense intensity',
            'Rain: Slight intensity',
            'Rain: Moderate intensity',
            'Rain: Heavy intensity',
            'Freezing Rain: Light intensity',
            'Freezing Rain: Heavy intensity',
            'Snow fall: Slight intensity',
            'Snow fall: Moderate intensity',
            'Snow fall: Heavy intensity',
            'Snow grains',
            'Rain showers: Slight',
            'Rain showers: Moderate',
            'Rain showers: Violent',
            'Snow showers: Slight',
            'Snow showers: Heavy',
            'Thunderstorm: Slight',
            'Thunderstorm: Moderate',
            'Thunderstorm: Violent'
        ];

        return [
            'id' => $this->faker->randomNumber(),
            'date' => $this->faker->optional()->dateTimeBetween('now', '+7 days')?->format('Y-m-d H:i:s'),
            'description' => $this->faker->randomElement($weatherDescriptions),
            'humidity' => $this->faker->randomFloat(2, 10, 1000),
            'icon' => $this->faker->numberBetween(1, 10),
            'wind_speed' => $this->faker->randomFloat(2, 10, 1000),
            'sunrise' => $this->faker->optional()->dateTimeBetween('now', '+7 days')?->format('Y-m-d H:i:s'),
            'sunset' => $this->faker->optional()->dateTimeBetween('now', '+7 days')?->format('Y-m-d H:i:s'),
            'temp_day' => $this->faker->randomFloat(2, 10, 1000),
            'temp_min' => $this->faker->randomFloat(2, 10, 1000),
            'temp_max' => $this->faker->randomFloat(2, 10, 1000),
            'temp_night' => $this->faker->randomFloat(2, 10, 1000),
            'temp_eve' => $this->faker->randomFloat(2, 10, 1000),
            'temp_morn' => $this->faker->randomFloat(2, 10, 1000),
            'pressure' => $this->faker->randomNumber(),
            'dew_point' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
