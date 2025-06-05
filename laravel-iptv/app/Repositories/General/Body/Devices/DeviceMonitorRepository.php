<?php

namespace App\Repositories\General\Body\Devices;

use App\Interfaces\General\Body\Devices\IDeviceMonitorRepository;
use App\Models\General\Body\Devices\Device;
use Illuminate\Support\Facades\Auth;

class DeviceMonitorRepository implements IDeviceMonitorRepository
{
    public function get_devices()
    {
        $devices = Device::with(['category', 'room'])
            ->get()
            ->map(function ($device) {
                return [
                    'name' => $device->category->name ?? 'Unknown',
                    'room_number' => $device->room->name ?? 'Unknown',
                    'ip4_address' => $device->ip4_address,
                    'mac_address' => $device->mac_address,
                    'current_status' => $device->current_status,
                ];
            });

        return response()->json($devices);
    }

    public function chartData($request)
    {
        $active = Device::where('current_status', 'Active')->count();
        $inactive = Device::where('current_status', 'Inactive')->count();


        // Prepare the results
        return response()->json([
            'active' => $active,
            'inactive' => $inactive,
        ]);
    }
}