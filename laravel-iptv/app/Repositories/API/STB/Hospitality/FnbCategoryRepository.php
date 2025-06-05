<?php

namespace App\Repositories\API\STB\Hospitality;

use App\Http\Resources\API\STB\Hospitality\FnbCategoryResource;
use App\Interfaces\API\STB\Hospitality\IFnbCategoryRepository;
use App\Models\General\Body\Hospitality\FnbCategory;

class FnbCategoryRepository implements IFnbCategoryRepository
{
    public function getFnbCategories($request)
    {
        $data = FnbCategory::get();

        return FnbCategoryResource::collection($data)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }
}