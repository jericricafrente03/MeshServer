<?php

namespace App\Repositories\API\STB\Hospitality;

use App\Http\Resources\API\STB\Hospitality\HotelInfoResource;
use App\Interfaces\API\STB\Hospitality\IHotelInfoRepository;
use App\Models\General\Body\Hospitality\HotelInfo;

class HotelInfoRepository implements IHotelInfoRepository
{
    public function getHotelInfos($request)
    {
        $data = HotelInfo::where('is_enable', 1)->get();

        return HotelInfoResource::collection($data)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }
}