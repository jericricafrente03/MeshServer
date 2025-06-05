<?php

namespace Database\Factories\General\Body\Tv;

use App\Models\General\Body\Tv\TvChannelCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\General\Body\Tv\TvChannelCategory>
 */
class TvChannelCategoryFactory extends Factory
{
    protected $model = TvChannelCategory::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $tvChannelCategories = [
            'International',
            'Sports, Action & Gaming',
            'Factual Entertainment',
            'Kids',
            'Music',
            'General Entertainment',
            'Lifestyle',
            'Movies',
            'News',
            'Religious',
        ];

        return [
            'id' => $this->faker->randomNumber(),
            'name' => $this->faker->randomElement($tvChannelCategories),
            'description' => 'Sample Description',
            'order_no' => $this->faker->numberBetween(1, 50),
        ];
    }
}
