<?php

namespace App\Http\Controllers\API\STB\Hospitality;

use App\Http\Controllers\Controller;
use App\Interfaces\API\STB\Hospitality\IServiceRequestRepository;
use Illuminate\Http\Request;

/**
* @group API ServiceRequestController
*
* Service Request.
*/
class ServiceRequestController extends Controller
{
    private $serviceRequestRepository;

    public function __construct(IServiceRequestRepository $serviceRequestRepository)
    {
        $this->serviceRequestRepository = $serviceRequestRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\Hospitality\ServiceRequestResource
     * @apiResourceModel App\Models\General\Body\Hospitality\HospitalityService
     * @apiResourceAdditional result=success
     */
    public function getServiceRequests(Request $request)
    {
        try {
            return $this->serviceRequestRepository->getServiceRequests($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
