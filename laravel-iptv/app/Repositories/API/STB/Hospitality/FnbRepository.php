<?php

namespace App\Repositories\API\STB\Hospitality;

use App\Http\Resources\API\STB\Hospitality\FnbResource;
use App\Http\Resources\API\STB\Hospitality\TopRecommendedFnbResource;
use App\Interfaces\API\STB\Hospitality\IFnbRepository;
use App\Models\General\Body\Hospitality\Fnb;
use App\Models\General\Body\Hospitality\TopRecommendedFnb;
use Illuminate\Support\Carbon;

class FnbRepository implements IFnbRepository
{
    public function getFnbs($request)
    {
        $data = Fnb::where('is_enable', 1)->get();

        return FnbResource::collection($data)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }

    public function getRecommendedFnbs($request)
    {
        $recommendationMonth = Carbon::now()->subMonth()->startOfMonth()->toDateString();

        $topFnbs = TopRecommendedFnb::select(
                'top_recommended_fnbs.popularity_score',
                'fnbs.id',
                'fnbs.name',
                'fnbs.description',
                'fnbs.unit_price',
                'fnbs.category_id',
                'fnbs.img_uri'
            )
            ->join('fnbs', 'fnbs.id', '=', 'top_recommended_fnbs.fnb_id')
            ->where('top_recommended_fnbs.recommended_month', $recommendationMonth)
            ->where('fnbs.is_enable', 1)
            ->orderByDesc('top_recommended_fnbs.popularity_score')
            ->get();
        
        // Format results
        $data = [
            'data' => $topFnbs->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'unit_price' => $item->unit_price,
                    'category_id' => 0, // Overriding category_id to 0 as in your CI3 code
                    'popularity_score' => $item->popularity_score,
                    'img_uri' => isset($item->img_uri) ? url($item->img_uri) : '',
                ];
            })
        ];

        return TopRecommendedFnbResource::collection($data)
            ->additional([
                    'result' => __('success'),
                ])
            ->response()
            ->setStatusCode(200); // HTTP status 200 OK
    }
}