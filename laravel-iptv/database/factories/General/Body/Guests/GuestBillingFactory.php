<?php

namespace Database\Factories\General\Body\Guests;

use App\Models\General\Body\Guests\GuestBilling;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class GuestBillingFactory extends Factory
{
    protected $model = GuestBilling::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $foodItems = [
            'Pizza',
            'Burger',
            'Sushi',
            'Pasta',
            'Salad',
            'Steak',
            'Tacos',
            'Ice Cream',
            'Samosa',
            'Fried Rice',
            'Dumplings',
            'Pad Thai',
            'Curry',
            'Hot Dog',
            'Nachos',
        ];

        $status = [
            'New Order',
            'Delivered',
            'Cancelled'
        ];
        
        return [
            'transaction_datetime' => $this->faker->dateTime(),
            'room_id' => $this->faker->numberBetween(1,1),
            'room_number' => $this->faker->numberBetween(101,101), //randomNumber(3),
            'category' => $this->faker->randomElement(['fnb', 'service_request', 'item_request']),
            'item_id' => $this->faker->randomNumber(),
            'item_name' => $this->faker->randomElement($foodItems),
            'quantity' => $this->faker->randomDigit(),
            'unit_price' => $this->faker->randomDigit(2, 100, 1000), // Up to 2 decimal points
            'refno' => $this->faker->numberBetween(1654654, 300645645),
            'status' => $this->faker->randomElement($status),
            'status_id' => $this->faker->numberBetween(11, 15),
            'room_assignment_id' => $this->faker->numberBetween(5,5),
            'user_id' => $this->faker->numberBetween(1, 1),
            'guest_name' => $this->faker->name(),
            'created_at' => $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
            'updated_at' => $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s'),
        ];
    }
}
