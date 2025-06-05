<?php

namespace App\Repositories\API\STB\Guests;

use App\Http\Resources\API\STB\Guests\RoomCategoryResource;
use App\Interfaces\API\STB\Guests\IRoomCategoryRepository;
use App\Models\General\Body\Guests\RoomCategory;

class RoomCategoryRepository implements IRoomCategoryRepository
{
    public function getRoomCategories($request)
    {
        $data = RoomCategory::get();

        return RoomCategoryResource::collection($data)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }
}