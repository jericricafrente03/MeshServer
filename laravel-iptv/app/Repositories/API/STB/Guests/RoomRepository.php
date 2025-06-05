<?php

namespace App\Repositories\API\STB\Guests;

use App\Http\Resources\API\STB\Guests\RoomResource;
use App\Interfaces\API\STB\Guests\IRoomRepository;
use App\Models\General\Body\Guests\Room;

class RoomRepository implements IRoomRepository
{
    public function getRooms($request)
    {
        $data = Room::with('status')->get();

        return RoomResource::collection($data)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }
}