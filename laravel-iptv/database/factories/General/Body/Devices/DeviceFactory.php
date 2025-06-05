<?php

namespace Database\Factories\General\Body\Devices;

use App\Models\General\Body\Devices\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\General\Body\Devices\Device>
 */
class DeviceFactory extends Factory
{
    protected $model = Device::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "device_id" => 5,
            "room" => "105",
            "username" => "c44eac205e6b",
            "version" => "5.0",
            // "action" => "UPDATE",
            "class" => "stb_register"
        ];
    }
}
