<?php

namespace App\Http\Controllers\API\STB\Guests;

use App\Http\Controllers\Controller;
use App\Interfaces\API\STB\Guests\IRoomRepository;
use Illuminate\Http\Request;

/**
* @group API RoomController
*
* Room.
*/
class RoomController extends Controller
{
    private $roomRepository;

    public function __construct(IRoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\Guests\RoomResource
     * @apiResourceModel App\Models\General\Body\Guests\Room
     * @apiResourceAdditional result=success
     */
    public function getRooms(Request $request)
    {
        try {
            return $this->roomRepository->getRooms($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
