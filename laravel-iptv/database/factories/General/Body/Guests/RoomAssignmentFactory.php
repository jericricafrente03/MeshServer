<?php

namespace Database\Factories\General\Body\Guests;

use App\Models\General\Body\Guests\RoomAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\General\Body\Guests\RoomAssignment>
 */
class RoomAssignmentFactory extends Factory
{
    protected $model = RoomAssignment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => $this->faker->randomNumber(),
            'room_id' => $this->faker->randomNumber(),
            'check_in' => $this->faker->dateTimeBetween('-7 days', 'now')->format('Y-m-d H:i:s'),
            'check_out' => $this->faker->optional()->dateTimeBetween('now', '+7 days')?->format('Y-m-d H:i:s'),
            's_check_out' => $this->faker->dateTimeBetween('+1 days', '+10 days')->format('Y-m-d H:i:s'),
            'reserve_no' => $this->faker->optional()->numerify('RES#####'),
            'cs' => $this->faker->randomElement([null, 1, 2, 3]),
            'gs' => $this->faker->randomElement([0, 1]),
            'is_checkout' => $this->faker->randomElement([0, 1]),
            // Embedded Guest Data
            'guest' => [
                'id' => $this->faker->randomNumber(),
                'title' => $this->faker->optional()->randomElement(['Mr', 'Ms', 'Dr', 'Engr']),
                'firstname' => $this->faker->firstName,
                'lastname' => $this->faker->lastName,
                'birthdate' => $this->faker->optional()->date(),
                'street1' => $this->faker->optional()->streetAddress,
                'street2' => $this->faker->optional()->secondaryAddress,
                'city' => $this->faker->optional()->city,
                'state_region' => $this->faker->optional()->state,
                'country_id' => (string) $this->faker->randomDigitNotNull,
                'zip_code' => $this->faker->optional()->postcode,
                'mobile_no' => $this->faker->optional()->phoneNumber,
                'landline_no' => $this->faker->optional()->phoneNumber,
                'email' => $this->faker->optional()->safeEmail,
            ],
        ];
    }
}
