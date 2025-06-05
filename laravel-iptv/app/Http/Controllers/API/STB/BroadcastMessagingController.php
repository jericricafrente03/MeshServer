<?php

namespace App\Http\Controllers\API\STB;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\STB\BroadcastMessagingRequest;
use App\Interfaces\API\STB\IBroadcastMessagingRepository;
use Illuminate\Http\Request;

/**
* @group API BroadcastMessagingController
*
* Broadcast Messaging.
*/
class BroadcastMessagingController extends Controller
{
    private $broadcastMessagingRepository;

    public function __construct(IBroadcastMessagingRepository $broadcastMessagingRepository)
    {
        $this->broadcastMessagingRepository = $broadcastMessagingRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResource status=200 App\Http\Resources\API\STB\BroadcastMessagingResource
     * @apiResourceModel App\Models\General\Body\Messages\BroadcastMessage
     * @apiResourceAdditional result=success
     */
    public function getBroadcastMessaging(BroadcastMessagingRequest $request)
    {
        try {
            return $this->broadcastMessagingRepository->getBroadcastMessaging($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
