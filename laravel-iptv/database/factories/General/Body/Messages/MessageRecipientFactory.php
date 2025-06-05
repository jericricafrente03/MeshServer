<?php

namespace Database\Factories\General\Body\Messages;

use App\Models\General\Body\Messages\MessageRecipient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\General\Body\Messages\MessageRecipient>
 */
class MessageRecipientFactory extends Factory
{
    protected $model = MessageRecipient::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status_id = $this->faker->randomNumber();
        $date = $this->faker->dateTimeBetween('now', '+7 days')?->format('Y-m-d H:i:s');

        return [
            'id' => $this->faker->randomNumber(),
            'regular_message_id' => $this->faker->randomNumber(),
            'room_id' => $this->faker->randomNumber(),
            'status_id' => $status_id,
            'created_at' => $date,
            'updated_at' => $date,
            'status' => [
                'id' => $status_id,
                'title' => $this->faker->randomElement(['Delivered', 'Seen']),
            ],
            'from' => 'Me',
            'subject' => 'Subject',
            'body' => '<p>Test</p>'
        ];
    }
}
