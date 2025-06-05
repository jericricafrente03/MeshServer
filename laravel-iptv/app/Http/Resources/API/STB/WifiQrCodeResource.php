<?php

namespace App\Http\Resources\API\STB;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WifiQrCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (env('APP_ENV') !== "scribe") {
            $data = parent::toArray($request);
        }
        else{
            $data = [
                "data" => [
                    "path" => 'http://192.168.110.29:8080/storage/upload/qr_code/192.168.110.204_900eb3510b0b.png'
                ],
                "message" => "QR Code generated successfully"
            ];
        }

        return $data;
    }
}
