<?php

namespace App\Repositories\API\STB\Hospitality;

use App\Http\Resources\API\STB\Hospitality\FacilityCategoryResource;
use App\Interfaces\API\STB\Hospitality\IFacilityCategoryRepository;
use App\Models\General\Body\Hospitality\FacilityCategory;

class FacilityCategoryRepository implements IFacilityCategoryRepository
{
    public function getFacilityCategories($request)
    {
        $data = FacilityCategory::get();

        return FacilityCategoryResource::collection($data)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }
}