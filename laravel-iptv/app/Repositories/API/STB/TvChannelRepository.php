<?php

namespace App\Repositories\API\STB;

use App\Http\Resources\API\STB\TvChannelResource;
use App\Interfaces\API\STB\ITvChannelRepository;
use App\Models\General\Body\Tv\TvChannel;

class TvChannelRepository implements ITvChannelRepository
{
    public function getTvChannels($request)
    {
        
        $tvChannels = TvChannel::where('is_enable', 1)->get();

        return TvChannelResource::collection($tvChannels)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }
}