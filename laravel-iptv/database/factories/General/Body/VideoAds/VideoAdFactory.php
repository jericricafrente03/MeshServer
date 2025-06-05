<?php

namespace Database\Factories\General\Body\VideoAds;

use App\Models\General\Body\VideoAds\VideoAd;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\General\Body\VideoAds\VideoAd>
 */
class VideoAdFactory extends Factory
{
    protected $model = VideoAd::class;
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
            'id' => 17,
            'name' => 'Test 22',
            'video_uri' => $systemUrl . 'storage/upload/video_ads/' . $this->faker->lexify('??????????????????????????????') . '.mp4',
            'is_enable' => 1,
            'order_no' => 1,
            'created_at' => now()->subDays(3)->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ];
    }
}
