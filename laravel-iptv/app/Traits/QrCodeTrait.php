<?php

namespace App\Traits;

use App\Models\General\Body\Devices\Device;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

trait QrCodeTrait
{
    /**
     * Generate WiFi QR Code
     *
     * @param string $macAddress
     * @param string $ipAddress
     * @return array
     */
    public function generateWifiQrCode($macAddress, $ipAddress)
    {
        // WiFi QR Code details
        $ssid = $macAddress;   // SSID
        $password = $macAddress; // Password
        $encryption = 'WPA';             // Encryption type (WPA, WEP, or empty for open networks)

        // WiFi QR Code format
        $wifiData = "WIFI:T:$encryption;S:$ssid;P:$password;;";

        // Define directory and file path
        $directoryPath = 'public/upload/qr_code';
        $fileName = "{$ipAddress}_{$macAddress}.png";
        $filePath = "$directoryPath/$fileName";

        // Ensure directory exists
        if (!Storage::exists($directoryPath)) {
            Storage::makeDirectory($directoryPath, 0755, true);
        }

        // Generate QR Code with GD
        // $qrCode = Builder::create()
        //     ->writer(new PngWriter()) // Use GD to generate PNG
        //     ->size(600) // Set size
        //     ->margin(10) // Set margin
        //     ->data($wifiData)
        //     ->build();

        $qrCode = $this->buildQrCode(600, 10, $wifiData);

        // Save the QR Code to storage
        Storage::put($filePath, $qrCode->getString());

        return [
            'path' => asset("storage/upload/qr_code/$fileName")
        ];
    }

    private function buildQrCode($size, $margin, $data)
    {
        // Generate QR Code with GD
        $qrCode = Builder::create()
            ->writer(new PngWriter()) // Use GD to generate PNG
            ->size($size) // Set size
            ->margin($margin) // Set margin
            ->data($data)
            ->build();

        return $qrCode;
    }
}