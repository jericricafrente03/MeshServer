<?php

namespace App\Repositories\API\STB;

use App\Http\Resources\API\STB\TvChannelCategoryResource;
use App\Interfaces\API\STB\ITvChannelCategoryRepository;
use App\Models\General\Body\Tv\TvChannelCategory;

class TvChannelCategoryRepository implements ITvChannelCategoryRepository
{
    public function getTvChannelCategories($request)
    {
        
        $tvChannelCategories = TvChannelCategory::get();

        return TvChannelCategoryResource::collection($tvChannelCategories)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }
}