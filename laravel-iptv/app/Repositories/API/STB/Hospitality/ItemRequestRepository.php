<?php

namespace App\Repositories\API\STB\Hospitality;

use App\Http\Resources\API\STB\Hospitality\ItemRequestResource;
use App\Interfaces\API\STB\Hospitality\IItemRequestRepository;
use App\Models\General\Body\Hospitality\HospitalityItem;

class ItemRequestRepository implements IItemRequestRepository
{
    public function getItemRequests($request)
    {
        $data = HospitalityItem::where('is_enable', 1)->get();

        return ItemRequestResource::collection($data)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }
}