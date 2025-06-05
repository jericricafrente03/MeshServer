<?php

namespace App\Http\Resources\API\STB\Guests;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomAssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data =  parent::toArray($request);

        // Remove created_at and updated_at from room_assignment
        unset($data['created_at'], $data['updated_at']);

        // If guest exists, remove created_at and updated_at from guest
        if (isset($data['guest'])) {
            unset($data['guest']['created_at'], $data['guest']['updated_at']);
        }

        return $data;
    }
}
