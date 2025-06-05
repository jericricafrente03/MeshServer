<?php

namespace Database\Factories\General\Body\Messages;

use App\Models\General\Body\Messages\BroadcastMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\General\Body\Messages\BroadcastMessage>
 */
class BroadcastMessageFactory extends Factory
{
    protected $model = BroadcastMessage::class;
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
            "id" => "5",
            "img_uri" => "{$systemUrl}storage/upload/broadcast_message/advertisements/bCE61HJr6cf1kAPPmQ4qxqSSYDLUN15pnVAMuceM.jpg",
            "duration" => "5",
            "message" => "This is Broadcast Messaging.",
            "broadcast_type" => [
                "id" => "6",
                "name" => "Ticker"
            ]
        ];
    }
}
