<?php

namespace App\Http\Controllers\API\STB\Hospitality;

use App\Http\Controllers\Controller;
use App\Interfaces\API\STB\Hospitality\IItemRequestRepository;
use Illuminate\Http\Request;

/**
* @group API ItemRequestController
*
* Item Request.
*/
class ItemRequestController extends Controller
{
    private $itemRequestRepository;

    public function __construct(IItemRequestRepository $itemRequestRepository)
    {
        $this->itemRequestRepository = $itemRequestRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\Hospitality\ItemRequestResource
     * @apiResourceModel App\Models\General\Body\Hospitality\HospitalityItem
     * @apiResourceAdditional result=success
     */
    public function getItemRequests(Request $request)
    {
        try {
            return $this->itemRequestRepository->getItemRequests($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
