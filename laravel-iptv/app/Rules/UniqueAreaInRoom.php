<?php

namespace App\Rules;

use App\Models\General\Body\Devices\Device;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueAreaInRoom implements ValidationRule
{
    protected $roomId;
    protected $macAddress;

    public function __construct($roomId, $macAddress)
    {
        $this->roomId = $roomId;
        $this->macAddress = $macAddress;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // $value is the area_id
        
        if (!$this->roomId || !$value) {
            $fail('Room ID or area ID is missing.');
            return;
        }

        $device = Device::where('room_id', $this->roomId)
                        ->where('area_id', $value)
                        ->first();

        if (!$device) {
            return; // Valid: no device found with this area_id and room_id
        }
        
        if ($device->mac_address !== $this->macAddress) {
            $fail('This area ID is already registered for this room.');
        }
    }
}
