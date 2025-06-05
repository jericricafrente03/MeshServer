<?php

namespace App\Repositories\API\STB;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\STB\BroadcastMessagingResource;
use App\Interfaces\API\STB\IBroadcastMessagingRepository;
use App\Models\General\Body\Messages\BroadcastMessage;

class BroadcastMessagingRepository extends Controller implements IBroadcastMessagingRepository
{
    public function getBroadcastMessaging($request)
    {
        $data = $request;
       
        $data = BroadcastMessage::with('broadcastType')->find($data['id']);
        if(!$data){
            return response()->json([
                'data' => [],
                'result' => 'failed',
                'message' => 'Record not found.'
            ], 404);
        }
        
        return BroadcastMessagingResource::make($data)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }
}