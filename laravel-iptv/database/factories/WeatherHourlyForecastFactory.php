<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WeatherHourlyForecast>
 */
class WeatherHourlyForecastFactory extends Factory
{
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
            'temp' => $this->faker->randomFloat(2, 10, 1000),
            'wind_speed' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
