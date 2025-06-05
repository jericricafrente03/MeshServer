<?php

namespace Database\Factories\SystemSettings\ThemeManager;

use App\Models\SystemSettings\ThemeManager\ThemeZone;
use App\Models\SystemSettings\ThemeManager\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SystemSettings\ThemeManager\Theme>
 */
class ThemeFactory extends Factory
{
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
            'id' => 2,
            'name' => 'Theme Name',
            'bg_uri' => "{$systemUrl}storage/upload/themes/" . $this->faker->lexify('??????????????????????????????') . '.png',
            'created_at' => now()->subDays(3)->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
            'zones' => [
                [
                    'id' => 2,
                    'name' => 'SampleZone',
                    'bg_uri' => $systemUrl.'storage/upload/theme_zones/' . $this->faker->lexify('??????????????????????????????') . '.png',
                    'text_color' => '#ffffff',
                    'active_text_color' => '#cccccc',
                ],
                [
                    'id' => 3,
                    'name' => 'SampleZone',
                    'bg_uri' => $systemUrl.'storage/upload/theme_zones/' . $this->faker->lexify('??????????????????????????????') . '.png',
                    'text_color' => '#ffffff',
                    'active_text_color' => '#cccccc',
                ]
            ],
            'applications' => [
                [
                    'id' => 2,
                    'name' => 'Sample App',
                    'method' => 'sample_method',
                    'icon' => $systemUrl.'storage/upload/theme_applications/icon/' . $this->faker->lexify('??????????????????????????????') . '.png',
                    'active_icon' => $systemUrl.'storage/upload/theme_applications/active_icon/' . $this->faker->lexify('??????????????????????????????') . '.png',
                    'text_color' => '#ffffff',
                    'active_text_color' => '#cccccc',
                    'order_no' => 2
                ],
                [
                    'id' => 3,
                    'name' => 'Sample App',
                    'method' => 'sample_method',
                    'icon' => $systemUrl.'storage/upload/theme_applications/icon/' . $this->faker->lexify('??????????????????????????????') . '.png',
                    'active_icon' => $systemUrl.'storage/upload/theme_applications/active_icon/' . $this->faker->lexify('??????????????????????????????') . '.png',
                    'text_color' => '#ffffff',
                    'active_text_color' => '#cccccc',
                    'order_no' => 3
                ]
            ]
        ];
    }

}