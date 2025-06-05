<?php

namespace App\Http\Controllers\API\STB\Guests;

use App\Http\Controllers\Controller;
use App\Interfaces\API\STB\Guests\IRoomCategoryRepository;
use Illuminate\Http\Request;

/**
* @group API RoomCategoryController
*
* Room Category.
*/
class RoomCategoryController extends Controller
{
    private $roomCategoryRepository;

    public function __construct(IRoomCategoryRepository $roomCategoryRepository)
    {
        $this->roomCategoryRepository = $roomCategoryRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\Guests\RoomCategoryResource
     * @apiResourceModel App\Models\General\Body\Guests\RoomCategory
     * @apiResourceAdditional result=success
     */
    public function getRoomCategories(Request $request)
    {
        try {
            return $this->roomCategoryRepository->getRoomCategories($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
