<?php

namespace App\Http\Resources\API\STB\Guests;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if (isset($data['status'])) {
            unset(
                $data['status']['created_at'], 
                $data['status']['updated_at'], 
                $data['status']['category'], 
                $data['status']['sort'], 
                $data['status']['icon'], 
                $data['status']['color'],
                $data['status']['bg_color'], 
                $data['status']['description']
            );
        }

        unset($data['created_at'], $data['updated_at'], $data['floor_number'], $data['room_status']);

        return $data;
    }
}
