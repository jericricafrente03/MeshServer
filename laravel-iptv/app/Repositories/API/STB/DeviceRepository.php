<?php

namespace App\Repositories\API\STB;

use App\Http\Resources\API\STB\LanguageResource;
use App\Http\Resources\API\STB\WifiQrCodeResource;
use App\Interfaces\API\STB\IDeviceRepository;
use App\Models\General\Body\Devices\Device;
use App\Models\General\Body\Guests\Room;
use App\Traits\QrCodeTrait;
use Illuminate\Support\Facades\DB;

class DeviceRepository implements IDeviceRepository
{
    use QrCodeTrait; 

    function getLanguage($request)
    {
        $data = $request;
       
        $room = Room::where('name', $data['room'])->first();
        if(!$room){
            return response()->json([
                'data' => [],
                'result' => 'failed',
                'message' => 'Record not found.'
            ], 404);
        } 

        $data = Device::with('language')
            ->where('room_id', $room->id)
            ->first();
        
        return LanguageResource::make($data)
            ->additional([
                    'result' => __('success'),
                ])
            ->response()
            ->setStatusCode(200); // HTTP status 200 OK
    }

    function getWifiQrCode($request)
    {
        $ipAddress = request()->ip();
        $device = Device::where('mac_address', $request->mac_address)->first();

        if (!$device) {
            return [
                'result' => 'failed',
                'message' => 'Device not found'
            ];
        }

        $data = $this->generateWifiQrCode($device->mac_address, $ipAddress);

        return WifiQrCodeResource::make($data)
            ->additional([
                    'result' => __('success'),
                    'message' => __('QR Code generated successfully'),
                ])
            ->response()
            ->setStatusCode(200); // HTTP status 200 OK
        // return response()->json($response);
    }

    function enterNetflix($request)
    {   
        $ipAddress = request()->ip();
        
        // $ipAddress = '192.168.110.45';
        // Start the queue worker temporarily to process the job
        $result = shell_exec(base_path()."/public/bash-scripts/adb_enter_netflix.sh '".$ipAddress."'");
        $lines = explode("\n", trim($result));

        // Prepare the JSON-like response
        $response = [
            'result' => $lines[0] ?? 'Failed'
        ];
        
        // sleep(1);
        return json_encode($response);
    }

    function clearCache($request)
    {   
        $ipAddress = request()->ip();
        
        // $ipAddress = '192.168.110.45';
        // Start the queue worker temporarily to process the job
        $result = shell_exec(base_path()."/public/bash-scripts/adb_clear_cache.sh '".$ipAddress."'");
        $lines = explode("\n", trim($result));

        // Prepare the JSON-like response
        $response = [
            'result' => $lines[0] ?? 'Failed'
        ];
        
        // sleep(1);
        return json_encode($response);
    }
}