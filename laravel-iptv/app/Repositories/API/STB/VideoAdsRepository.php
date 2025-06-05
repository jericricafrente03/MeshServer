<?php

namespace App\Repositories\API\STB;

use App\Http\Resources\API\STB\VideoAdsResource;
use App\Interfaces\API\STB\IVideoAdsRepository;
use App\Models\General\Body\Guests\Room;
use App\Models\General\Body\VideoAds\VideoAd;
use App\Models\General\Body\VideoAds\VideoAdsRoom;

class VideoAdsRepository implements IVideoAdsRepository
{
    function getVideoAds($request)
    {
        $data = $request;
       
        $room = Room::where('name', $data['room'])->first();
        if(!$room){
            return response()->json([
                'data' => [],
                'result' => 'failed',
                'message' => 'Record not found.'
            ], 404);
        } 
        // Get video ads linked to the room where is_enable == 1
        $videoAds = VideoAd::where('is_enable', 1)
            ->whereHas('videoAdsRooms', function ($query) use ($room) {
                $query->where('room_id', $room->id);
            })
            ->get();
        
        return VideoAdsResource::collection($videoAds)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }
}