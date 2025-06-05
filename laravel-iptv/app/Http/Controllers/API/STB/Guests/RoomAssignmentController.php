<?php

namespace App\Http\Controllers\API\STB\Guests;

use App\Http\Controllers\Controller;
use App\Interfaces\API\STB\Guests\IRoomAssignmentRepository;
use Illuminate\Http\Request;

/**
* @group API RoomAssignment Controller
*
* Room Assignment.
*/
class RoomAssignmentController extends Controller
{
    private $roomAssignmentRepository;

    public function __construct(IRoomAssignmentRepository $roomAssignmentRepository)
    {
        $this->roomAssignmentRepository = $roomAssignmentRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResource status=200 App\Http\Resources\API\STB\Guests\RoomAssignmentResource
     * @apiResourceModel App\Models\General\Body\Guests\RoomAssignment
     * @apiResourceAdditional result=success
     */
    public function getCustomer(Request $request)
    {
        try {
            return $this->roomAssignmentRepository->getCustomer($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
